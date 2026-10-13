<?php

namespace Tests\Feature;

use App\Models\ArahanDetailModel;
use App\Models\ArahanModel;
use App\Models\AssessmentDetailModel;
use App\Models\AssessmentModel;
use App\Models\MasterFrameworkModel;
use App\Models\MasterIndikatorModel;
use App\Models\MasterKategoriModel;
use App\Models\MasterModuleModel;
use App\Models\MasterRoleModel;
use App\Models\MasterRolePermissionModel;
use App\Models\employeeModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RbacAccessTest extends TestCase
{
    use DatabaseTransactions;

    private array $users = [];

    private AssessmentDetailModel $assessmentDetail;

    private ArahanModel $arahan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();

        foreach (['Admin', 'Assessor', 'Tim GCG', 'User'] as $roleName) {
            $this->users[$roleName] = $this->makeEmployee($roleName);
        }

        $framework = MasterFrameworkModel::create([
            'nama_framework' => 'RBAC Test Framework',
            'kode_framework' => 'RBAC-TEST',
            'versi' => '1.0',
            'tahun_berlaku' => 2026,
            'status' => 'Active',
        ]);
        $kategori = MasterKategoriModel::create([
            'framework_id' => $framework->id,
            'nama_kategori' => 'RBAC Test Category',
            'bobot' => 1,
        ]);
        $indikator = MasterIndikatorModel::create([
            'kategori_id' => $kategori->id,
            'kode_indikator' => 'RBAC-001',
            'indikator' => 'RBAC test indicator',
            'bobot' => 1,
        ]);
        $assessment = AssessmentModel::create([
            'jenis_asesmen' => 'Self Assessment',
            'tahun_asesmen' => 2026,
            'tahun_buku' => 2026,
            'framework_id' => $framework->id,
            'skor' => 0,
        ]);
        $this->assessmentDetail = AssessmentDetailModel::create([
            'assessment_id' => $assessment->id,
            'framework_id' => $framework->id,
            'kategori_id' => $kategori->id,
            'indikator_id' => $indikator->id,
            'eviden' => null,
            'dokumen_softcopy' => null,
            'bobot' => 1,
            'pemenuhan' => 'Tidak',
        ]);

        $this->arahan = ArahanModel::create([
            'judul_arahan' => 'RBAC Test Arahan',
            'tanggal_arahan' => '2026-01-01',
            'progress' => 0,
        ]);
        ArahanDetailModel::create([
            'arahan_id' => $this->arahan->id,
            'aspek' => 'RBAC Test Aspect',
            'arahan' => 'RBAC test direction',
            'tindak_lanjut' => null,
            'status' => 'Open',
            'eviden' => null,
        ]);
    }

    public function test_admin_can_access_all_modules(): void
    {
        $this->actingAs($this->users['Admin']);

        foreach ([
            '/dashboard',
            '/assessment',
            '/arahan',
            '/employee',
            '/master_framework',
            '/master_kategori',
            '/master_indikator',
            '/master_role',
            '/master_module',
            '/role_permission',
        ] as $uri) {
            $response = $this->get($uri);
            $this->assertNotSame(403, $response->getStatusCode(), $uri . ' unexpectedly returned 403.');
        }
    }

    public function test_assessor_can_update_score_and_evidence_but_cannot_create_assessment(): void
    {
        $this->actingAs($this->users['Assessor']);

        $this->put(route('assessment_detail.update', $this->assessmentDetail->id), [
            'eviden' => 'Evidence entered by assessor',
            'pemenuhan' => 'Ya',
        ])->assertRedirect();

        $this->assertDatabaseHas('assessment_details', [
            'id' => $this->assessmentDetail->id,
            'eviden' => 'Evidence entered by assessor',
            'pemenuhan' => 'Ya',
        ]);

        $this->post(route('assessment.store'), [
            'jenis_asesmen' => 'Self Assessment',
            'tahun_asesmen' => 2026,
            'tahun_buku' => 2026,
            'framework_id' => $this->assessmentDetail->framework_id,
        ])->assertForbidden();
    }

    public function test_tim_gcg_can_create_assessment_and_update_evidence_but_cannot_update_score(): void
    {
        $this->actingAs($this->users['Tim GCG']);

        $this->get(route('assessment.create'))->assertOk();
        $this->put(route('assessment_detail.update', $this->assessmentDetail->id), [
            'eviden' => 'Evidence entered by Tim GCG',
            'pemenuhan' => 'Ya',
        ])->assertRedirect();

        $this->assertDatabaseHas('assessment_details', [
            'id' => $this->assessmentDetail->id,
            'eviden' => 'Evidence entered by Tim GCG',
            'pemenuhan' => 'Tidak',
        ]);

        $this->assertDatabaseMissing('assessment_details', [
            'id' => $this->assessmentDetail->id,
            'pemenuhan' => 'Ya',
        ]);
    }

    public function test_user_has_view_only_access(): void
    {
        $this->actingAs($this->users['User']);

        $this->get(route('assessment_detail.index', $this->assessmentDetail->assessment_id))
            ->assertOk();
        $this->get(route('arahan_detail.index', $this->arahan->id))
            ->assertOk();
        $this->put(route('assessment_detail.update', $this->assessmentDetail->id), [
            'eviden' => 'Unauthorized evidence',
            'pemenuhan' => 'Ya',
        ])->assertForbidden();
    }

    public function test_assessor_and_tim_gcg_cannot_edit_arahan(): void
    {
        foreach (['Assessor', 'Tim GCG'] as $roleName) {
            $this->actingAs($this->users[$roleName]);

            $this->get(route('arahan.edit', $this->arahan->id))->assertForbidden();
            $this->post(route('arahan.store'), [
                'judul_arahan' => 'Unauthorized',
                'tanggal_arahan' => '2026-01-01',
            ])->assertForbidden();
        }
    }

    public function test_unauthorized_direct_urls_return_forbidden(): void
    {
        $this->actingAs($this->users['User']);

        $this->get(route('assessment.create'))->assertForbidden();
        $this->get(route('employee.index'))->assertForbidden();
        $this->get(route('master_framework.index'))->assertForbidden();
        $this->get(route('arahan.edit', $this->arahan->id))->assertForbidden();
    }

    private function seedRolesAndPermissions(): void
    {
        $roles = [];
        foreach (['Admin', 'Assessor', 'Tim GCG', 'User'] as $name) {
            $roles[$name] = MasterRoleModel::firstOrCreate(['nama_role' => $name]);
        }

        $assessment = MasterModuleModel::firstOrCreate(
            ['slug' => 'assessment'],
            ['nama_module' => 'Assessment']
        );
        $arahan = MasterModuleModel::firstOrCreate(
            ['slug' => 'arahan'],
            ['nama_module' => 'Arahan']
        );

        foreach ($roles as $roleName => $role) {
            if ($roleName === 'Admin') {
                continue;
            }

            $assessmentPermissions = match ($roleName) {
                'Assessor' => ['can_create' => 0, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 0],
                'Tim GCG' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 0],
                default => ['can_create' => 0, 'can_read' => 1, 'can_update' => 0, 'can_delete' => 0],
            };
            MasterRolePermissionModel::updateOrCreate(
                ['role_id' => $role->id, 'module_id' => $assessment->id],
                $assessmentPermissions
            );
            MasterRolePermissionModel::updateOrCreate(
                ['role_id' => $role->id, 'module_id' => $arahan->id],
                ['can_create' => 0, 'can_read' => 1, 'can_update' => 0, 'can_delete' => 0]
            );
        }
    }

    private function makeEmployee(string $roleName): employeeModel
    {
        $role = MasterRoleModel::where('nama_role', $roleName)->firstOrFail();
        $suffix = strtolower(str_replace(' ', '-', $roleName)) . '-' . uniqid();

        return employeeModel::create([
            'nama' => 'RBAC Test ' . $roleName,
            'nip' => 'TEST-' . strtoupper(substr(md5($suffix), 0, 10)),
            'Jabatan' => 'Tester',
            'tanggal_lahir' => null,
            'direktorat' => 'IT',
            'bidang' => 'Testing',
            'email' => $suffix . '@example.test',
            'role_id' => $role->id,
            'password' => Hash::make('password'),
        ]);
    }
}
