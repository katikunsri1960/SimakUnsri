<?php

use App\Models\ProgramStudi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->integer('id_jurusan')->nullable()->after('id');
        });

        $data = [
            ['id_prodi' => '1158a8f5-44c7-40a1-9f95-744680485286', 'id_jurusan' => 701, 'id_fakultas' => 7],
            ['id_prodi' => '74476eb0-7173-41bc-b6f4-a6fedec7abfb', 'id_jurusan' => 701, 'id_fakultas' => 7],
            ['id_prodi' => '9e800fce-bd75-41fd-be96-fdaf1f77a792', 'id_jurusan' => 701, 'id_fakultas' => 7],
            ['id_prodi' => '29fce0d4-b21b-4c8b-8f73-ead22be4fd4f', 'id_jurusan' => 501, 'id_fakultas' => 5],
            ['id_prodi' => '314e63d9-7b3e-4fb8-91ce-bc50d49476f5', 'id_jurusan' => 508, 'id_fakultas' => 5],
            ['id_prodi' => 'a575e707-2afd-4d2d-b872-4f329b9d34f5', 'id_jurusan' => 502, 'id_fakultas' => 5],
            ['id_prodi' => 'f4daecce-0a4d-4ba3-a7b0-4bbfacfe6c9c', 'id_jurusan' => 502, 'id_fakultas' => 5],
            ['id_prodi' => '55af75a9-876c-4589-a80a-9faa419614bf', 'id_jurusan' => 101, 'id_fakultas' => 1],
            ['id_prodi' => 'c99559ea-c795-477a-9f77-d4ce398499a2', 'id_jurusan' => 101, 'id_fakultas' => 1],
            ['id_prodi' => '947760c7-8b9b-40d2-af81-cdd141fddadb', 'id_jurusan' => 401, 'id_fakultas' => 4],
            ['id_prodi' => 'db130682-b3cf-486f-a30c-7fc84c31c4c5', 'id_jurusan' => 305, 'id_fakultas' => 3],
            ['id_prodi' => '90c23123-fb1e-48ee-9bdc-f923e799cd2a', 'id_jurusan' => 403, 'id_fakultas' => 4],
            ['id_prodi' => '6664214b-8565-4b33-adee-5449b3e28520', 'id_jurusan' => 601, 'id_fakultas' => 6],
            ['id_prodi' => 'c09eed95-426d-4c1f-ae40-7d1bf3f9344d', 'id_jurusan' => 801, 'id_fakultas' => 8],
            ['id_prodi' => 'f650ca52-af68-4484-8d31-ca31261d598b', 'id_jurusan' => 801, 'id_fakultas' => 8],
            ['id_prodi' => 'c4bbd3bb-3b4b-4aa3-bc50-136842747c67', 'id_jurusan' => 406, 'id_fakultas' => 4],
            ['id_prodi' => '8cf15154-6bbd-47e7-8200-c7a3f9d37a0b', 'id_jurusan' => 507, 'id_fakultas' => 5],
            ['id_prodi' => '9141fef1-e483-4cf1-ab1c-436507ec79f2', 'id_jurusan' => 102, 'id_fakultas' => 1],
            ['id_prodi' => '7bb05154-c149-463b-a2fd-fc60fbc4f503', 'id_jurusan' => 802, 'id_fakultas' => 8],
            ['id_prodi' => '4a623e2b-9e25-45a6-ba19-5d390ac7425b', 'id_jurusan' => 803, 'id_fakultas' => 8],
            ['id_prodi' => '6862a2c2-0357-4f0d-9f47-51cced2cc817', 'id_jurusan' => 803, 'id_fakultas' => 8],
            ['id_prodi' => '57469554-a7ba-4390-8e93-6a31b7950ae3', 'id_jurusan' => 1001, 'id_fakultas' => 10],
            ['id_prodi' => 'b2166a55-f878-4eec-9044-26bdc857ca53', 'id_jurusan' => 701, 'id_fakultas' => 7],
            ['id_prodi' => 'aa655621-20e4-498a-bb28-5c629b914a58', 'id_jurusan' => 101, 'id_fakultas' => 1],
            ['id_prodi' => 'a77fda16-ec5a-4d73-b076-51bac9b88ae4', 'id_jurusan' => 402, 'id_fakultas' => 4],
            ['id_prodi' => '5df0cd27-07b5-488f-801d-c08b21b33ed1', 'id_jurusan' => 102, 'id_fakultas' => 1],
            ['id_prodi' => 'c41d6e01-459d-4789-badc-c26717c4828c', 'id_jurusan' => 102, 'id_fakultas' => 1],
            ['id_prodi' => '78f127ef-0d90-4b1f-a794-f8a821ca476c', 'id_jurusan' => 702, 'id_fakultas' => 7],
            ['id_prodi' => '7a9f8811-50e4-41f3-8cf0-ade1c22a9d68', 'id_jurusan' => 201, 'id_fakultas' => 2],
            ['id_prodi' => '8f0eea14-5dbc-40ea-a5ce-47b78de8a184', 'id_jurusan' => 201, 'id_fakultas' => 2],
            ['id_prodi' => 'f4f7b843-a9ec-413e-ae33-9fe601e6d2b4', 'id_jurusan' => 201, 'id_fakultas' => 2],
            ['id_prodi' => '73cd2292-67e9-43cb-a83f-9ecc5412365a', 'id_jurusan' => 412, 'id_fakultas' => 4],
            ['id_prodi' => 'd8fc7d99-9d8a-4484-b946-3d1e7680314b', 'id_jurusan' => 804, 'id_fakultas' => 8],
            ['id_prodi' => '857cd112-bb6c-4ea9-9fe7-6782290c61e0', 'id_jurusan' => 408, 'id_fakultas' => 4],
            ['id_prodi' => '132e62cc-dfdc-437d-9df3-e5317f80a6ff', 'id_jurusan' => 409, 'id_fakultas' => 4],
            ['id_prodi' => 'e1ea335b-fe3f-4fc2-af88-3051a6319040', 'id_jurusan' => 1002, 'id_fakultas' => 10],
            ['id_prodi' => '5c1370e1-dfd1-4137-af50-a24025696602', 'id_jurusan' => 901, 'id_fakultas' => 9],
            ['id_prodi' => '63436d60-3629-47b8-b393-f36a7b5c5cd0', 'id_jurusan' => 703, 'id_fakultas' => 7],
            ['id_prodi' => 'd06d7dd8-595e-40ce-9175-29680ce0bc21', 'id_jurusan' => 1103, 'id_fakultas' => 11],
            ['id_prodi' => '2282d1e5-9e12-4c79-a33f-5579763f7f94', 'id_jurusan' => 103, 'id_fakultas' => 1],
            ['id_prodi' => 'aae16080-e0e9-4e19-8af9-18e93d5c4047', 'id_jurusan' => 103, 'id_fakultas' => 1],
            ['id_prodi' => '845702ca-79f5-4822-b191-29f22e79c5f4', 'id_jurusan' => 807, 'id_fakultas' => 8],
            ['id_prodi' => '6995b0ac-8b3b-4561-9453-87d4b49fc51d', 'id_jurusan' => 1101, 'id_fakultas' => 11],
            ['id_prodi' => '67c6cb06-f882-48c2-8a8f-33ab9457d1a6', 'id_jurusan' => 413, 'id_fakultas' => 4],
            ['id_prodi' => '40693f4c-5177-4bd3-b3df-7321320583a6', 'id_jurusan' => 405, 'id_fakultas' => 4],
            ['id_prodi' => '95290672-5f13-4776-9c0e-9c84ff0611ed', 'id_jurusan' => 405, 'id_fakultas' => 4],
            ['id_prodi' => 'e2f2ac47-8844-456b-b525-482db9da0abf', 'id_jurusan' => 404, 'id_fakultas' => 4],
            ['id_prodi' => 'bb06fc41-9e48-443e-aa02-df83da6bb467', 'id_jurusan' => 410, 'id_fakultas' => 4],
            ['id_prodi' => 'b3dce9a8-25b8-4f27-96cc-2abe5e0d9fa9', 'id_jurusan' => 411, 'id_fakultas' => 4],
            ['id_prodi' => '9965f1cf-563f-4671-9dca-4874e8c5d075', 'id_jurusan' => 415, 'id_fakultas' => 4],
            ['id_prodi' => '58d500c4-92c1-4254-be20-331853f71480', 'id_jurusan' => 506, 'id_fakultas' => 5],
            ['id_prodi' => '7c16e556-84d0-4e91-b0ef-75cf2f2776c6', 'id_jurusan' => 509, 'id_fakultas' => 5],
            ['id_prodi' => '529deff0-fa0b-45ed-a407-f55d021dd0ef', 'id_jurusan' => 504, 'id_fakultas' => 5],
            ['id_prodi' => '1cd691d4-0773-40bf-b857-c79e073be783', 'id_jurusan' => 306, 'id_fakultas' => 3],
            ['id_prodi' => 'efd6f97f-d7fc-42c1-bea0-2e5837e569d6', 'id_jurusan' => 407, 'id_fakultas' => 4],
            ['id_prodi' => '76c29e2e-b39d-4e87-b6e7-087154ea7054', 'id_jurusan' => 202, 'id_fakultas' => 2],
            ['id_prodi' => 'd1558c83-f092-42bd-bcd0-ee07d01bc342', 'id_jurusan' => 1102, 'id_fakultas' => 11],
            ['id_prodi' => 'e3632235-463b-46b5-9655-0d15306c2dcb', 'id_jurusan' => 1003, 'id_fakultas' => 10],
            ['id_prodi' => '64ccceb7-01ab-4cc8-89dc-0ad62bd8c3f4', 'id_jurusan' => 1002, 'id_fakultas' => 10],
            ['id_prodi' => '98da3402-7f1b-43ff-aa94-2641b014e804', 'id_jurusan' => 103, 'id_fakultas' => 1],
            ['id_prodi' => 'cc2619a2-8496-476a-9747-ad7fba0126b9', 'id_jurusan' => 805, 'id_fakultas' => 8],
            ['id_prodi' => 'eb1dc517-8411-4cce-a119-ca62055fe3b7', 'id_jurusan' => 805, 'id_fakultas' => 8],
            ['id_prodi' => '279fe949-3c1e-4ba6-98c9-17f486ca8883', 'id_jurusan' => 902, 'id_fakultas' => 9],
            ['id_prodi' => '6090ff04-6c12-47e5-836f-3b27d6aefd6d', 'id_jurusan' => 103, 'id_fakultas' => 1],
            ['id_prodi' => 'ee87d53e-8dea-48ae-8310-11d3504220e3', 'id_jurusan' => 103, 'id_fakultas' => 1],
            ['id_prodi' => 'b9530db8-86c3-41fa-a23e-bba70596f5ff', 'id_jurusan' => 902, 'id_fakultas' => 9],
            ['id_prodi' => 'd2dc6528-ceb3-439a-abad-afaf21ec26fe', 'id_jurusan' => 806, 'id_fakultas' => 8],
            ['id_prodi' => 'a69ad991-0518-42f8-8f0c-7b0c62ccd7b9', 'id_jurusan' => 412, 'id_fakultas' => 4],
            ['id_prodi' => 'fd61ecb4-d6b0-4135-b7e2-7e23665c3e0d', 'id_jurusan' => 402, 'id_fakultas' => 4],
            ['id_prodi' => 'eea5ce39-ce9f-4d42-99fd-fe2dda03647f', 'id_jurusan' => 607, 'id_fakultas' => 6],
            ['id_prodi' => '898361bf-ded5-43e4-b458-69e4ea371f59', 'id_jurusan' => 602, 'id_fakultas' => 6],
            ['id_prodi' => '32145166-da75-43b6-a4fa-1e4b77c007f9', 'id_jurusan' => 602, 'id_fakultas' => 6],
            ['id_prodi' => '3f95b922-31ea-4442-93fc-480385968f84', 'id_jurusan' => 602, 'id_fakultas' => 6],
            ['id_prodi' => '4d72edf0-b1ea-4960-a928-9d31763e380c', 'id_jurusan' => 604, 'id_fakultas' => 6],
            ['id_prodi' => '7c569912-fa48-4b93-8c16-1fc78969c337', 'id_jurusan' => 406, 'id_fakultas' => 4],
            ['id_prodi' => '438c7612-d1e2-4e48-940c-0d2bb76cbc3c', 'id_jurusan' => 603, 'id_fakultas' => 6],
            ['id_prodi' => '578323d9-ec30-41ad-bedd-8ac5204a3144', 'id_jurusan' => 604, 'id_fakultas' => 6],
            ['id_prodi' => '89808389-dd89-41b9-8169-884b4e5b7188', 'id_jurusan' => 604, 'id_fakultas' => 6],
            ['id_prodi' => '3b70c651-58bb-4730-bc94-8a610a358162', 'id_jurusan' => 601, 'id_fakultas' => 6],
            ['id_prodi' => 'f9e38022-9943-4d11-a5dd-c86902428356', 'id_jurusan' => 601, 'id_fakultas' => 6],
            ['id_prodi' => 'c4802782-e00f-4b19-a464-09fdbf4538aa', 'id_jurusan' => 601, 'id_fakultas' => 6],
            ['id_prodi' => '23eded88-d2fe-41ed-a039-a59c7d58a3bb', 'id_jurusan' => 604, 'id_fakultas' => 6],
            ['id_prodi' => 'ba2621a6-52f1-4579-b6ab-6d3c0c42e028', 'id_jurusan' => 606, 'id_fakultas' => 6],
            ['id_prodi' => '01fc0290-2992-4159-a3c3-21af8bf580fe', 'id_jurusan' => 601, 'id_fakultas' => 6],
            ['id_prodi' => '99ad4fc5-a08c-4a67-82ed-7843460d290e', 'id_jurusan' => 604, 'id_fakultas' => 6],
            ['id_prodi' => 'aabb694e-d7ad-4d3f-8db5-618ea60c0015', 'id_jurusan' => 604, 'id_fakultas' => 6],
            ['id_prodi' => 'd6f315de-b934-4dfd-a5bc-49ca457a6674', 'id_jurusan' => 604, 'id_fakultas' => 6],
            ['id_prodi' => '1b27dfc7-64d7-4a75-b0e1-9e059d2e1f36', 'id_jurusan' => 601, 'id_fakultas' => 6],
            ['id_prodi' => 'ede4014d-cd37-4bea-96e1-51e8536c6180', 'id_jurusan' => 603, 'id_fakultas' => 6],
            ['id_prodi' => '7666b6f4-1d8c-48ea-a0d7-aed989d44b02', 'id_jurusan' => 802, 'id_fakultas' => 8],
            ['id_prodi' => 'ab88c952-7132-4b1d-8d05-2380d8a40899', 'id_jurusan' => 605, 'id_fakultas' => 6],
            ['id_prodi' => '1024b687-cdc1-4f5e-a79a-20e60cb6b63b', 'id_jurusan' => 603, 'id_fakultas' => 6],
            ['id_prodi' => '5d5da936-e321-49d0-bf81-2a129ab7665f', 'id_jurusan' => 601, 'id_fakultas' => 6],
            ['id_prodi' => '11b33597-a128-4e87-b415-5d200e195236', 'id_jurusan' => 1104, 'id_fakultas' => 11],
            ['id_prodi' => '3e7a39dc-04d2-4388-a6a0-a8d99196f000', 'id_jurusan' => 510, 'id_fakultas' => 5],
            ['id_prodi' => '17eaa285-8f42-4cd5-890f-aa85f0182d85', 'id_jurusan' => 608, 'id_fakultas' => 6],
            ['id_prodi' => 'c9f5b196-dd7e-4788-a6e8-724046a1c344', 'id_jurusan' => 101, 'id_fakultas' => 1],
            ['id_prodi' => '98223413-b27d-4afe-a2b8-d0d80173506e', 'id_jurusan' => 406, 'id_fakultas' => 4],
            ['id_prodi' => 'be779246-fe70-4e66-8fa2-8929d97779a2', 'id_jurusan' => 407, 'id_fakultas' => 4],
            ['id_prodi' => '91360393-8632-4240-bed0-bfc707406efa', 'id_jurusan' => 408, 'id_fakultas' => 4],
            ['id_prodi' => 'b68efc34-c0f0-4334-9970-e02d769e3f49', 'id_jurusan' => 307, 'id_fakultas' => 3],
            ['id_prodi' => '7ef163fc-efa7-45c4-b8c9-80233654cde7', 'id_jurusan' => 503, 'id_fakultas' => 5],
            ['id_prodi' => 'a8d4f70f-406c-43f6-95ee-15f8ad836db3', 'id_jurusan' => 414, 'id_fakultas' => 4],
            ['id_prodi' => 'a8ac1bc7-9619-4578-a37d-c6f2713c07b4', 'id_jurusan' => 609, 'id_fakultas' => 6],
            ['id_prodi' => '54f95c08-24b4-4097-8a08-b1a687fa875e', 'id_jurusan' => 610, 'id_fakultas' => 6],
            ['id_prodi' => 'e564fce3-db6e-42fc-9ea9-c826f59b3ed8', 'id_jurusan' => 611, 'id_fakultas' => 6],
            ['id_prodi' => 'be2bc76c-9959-4e1d-aae5-a33d0eb48d30', 'id_jurusan' => 612, 'id_fakultas' => 6],
            ['id_prodi' => 'a24dd6ca-eb68-4364-ab1f-50244ea14051', 'id_jurusan' => 613, 'id_fakultas' => 6],
            ['id_prodi' => 'f372256f-a92d-491d-b313-7b64a9fcbe3a', 'id_jurusan' => 614, 'id_fakultas' => 6],
            ['id_prodi' => 'e2bef317-589c-4225-88b4-dbbfab8bc29d', 'id_jurusan' => 615, 'id_fakultas' => 6],
            ['id_prodi' => '771a2886-2ac2-4b35-9b9a-edfea0485278', 'id_jurusan' => 616, 'id_fakultas' => 6],
            ['id_prodi' => '06218eef-4721-4cd9-85a4-a40253a6b450', 'id_jurusan' => 617, 'id_fakultas' => 6],
            ['id_prodi' => 'f1514394-5ad3-4330-b8ad-b862d7cff293', 'id_jurusan' => 618, 'id_fakultas' => 6],
            ['id_prodi' => '6343967c-d7e3-447c-86a4-37c5c166ad7a', 'id_jurusan' => 406, 'id_fakultas' => 4],
            ['id_prodi' => '68008580-255a-4e4d-b0ef-40605bc2bedd', 'id_jurusan' => 902, 'id_fakultas' => 9],
            ['id_prodi' => '8bfd181c-798d-4078-a474-f6e801c14d41', 'id_jurusan' => 903, 'id_fakultas' => 9],
            ['id_prodi' => '14463b14-a07a-45ee-a1fc-9d0b64a49ec2', 'id_jurusan' => 704, 'id_fakultas' => 7],
            ['id_prodi' => '77e824cb-2142-425e-a85a-322633173319', 'id_jurusan' => 704, 'id_fakultas' => 7],
            ['id_prodi' => '88a3482b-3cc8-4beb-9d48-c3da2f1501bd', 'id_jurusan' => 305, 'id_fakultas' => 3],
            ['id_prodi' => 'ce967ed4-af20-4283-894a-25fbde511b97', 'id_jurusan' => 301, 'id_fakultas' => 3],
            ['id_prodi' => '25ca5240-19b3-49aa-a55a-1a59b76c2b63', 'id_jurusan' => 304, 'id_fakultas' => 3],
            ['id_prodi' => '0bfbc20c-86dc-4b69-b098-31788d5e9a04', 'id_jurusan' => 904, 'id_fakultas' => 9],
            ['id_prodi' => 'c9091879-6fd9-4691-bea8-283186c27ad1', 'id_jurusan' => 904, 'id_fakultas' => 9],
            ['id_prodi' => '1bde2344-501e-493a-92e1-bee0890481a0', 'id_jurusan' => 302, 'id_fakultas' => 3],
            ['id_prodi' => 'a3b6a695-75fd-4247-aec3-d9ebdabb671b', 'id_jurusan' => 302, 'id_fakultas' => 3],
            ['id_prodi' => '47743d2b-4e04-4115-80f8-a1b8dd1d4584', 'id_jurusan' => 903, 'id_fakultas' => 9],
            ['id_prodi' => '9736f30a-3992-42c6-b005-3c10e91afd14', 'id_jurusan' => 303, 'id_fakultas' => 3],
            ['id_prodi' => 'f371d293-c602-4b1b-afc5-222081477091', 'id_jurusan' => 303, 'id_fakultas' => 3],
            ['id_prodi' => '5f93f7d9-eb85-46c1-9714-e00db90938e3', 'id_jurusan' => 304, 'id_fakultas' => 3],
            ['id_prodi' => 'fa919a76-6f88-46e8-ae56-26bf61f70338', 'id_jurusan' => 304, 'id_fakultas' => 3],
            ['id_prodi' => '97a106e6-818d-43f9-8873-16b86d2bc7bb', 'id_jurusan' => 511, 'id_fakultas' => 5],
            ['id_prodi' => '05eb6093-52ca-4f28-8088-82398983d456', 'id_jurusan' => 305, 'id_fakultas' => 3],
            ['id_prodi' => '851e4469-1483-4158-8b7f-65cd62233ef6', 'id_jurusan' => 305, 'id_fakultas' => 3],
            ['id_prodi' => 'f7d525fa-ba3d-42b2-9983-7b82e9353d7a', 'id_jurusan' => 507, 'id_fakultas' => 5],
            ['id_prodi' => 'e136ff06-7eac-486d-987a-6eaf00ada885', 'id_jurusan' => 511, 'id_fakultas' => 5],
            ['id_prodi' => 'd992b771-f5e5-4950-9041-152af0c67339', 'id_jurusan' => 512, 'id_fakultas' => 5],
            ['id_prodi' => '1d007a87-3c2a-489a-8530-51f21f34a6e5', 'id_jurusan' => 601, 'id_fakultas' => 6],
        ];

        foreach ($data as $key => $value) {
            ProgramStudi::where('id_prodi', $value['id_prodi'])->update([
                'id_jurusan' => $value['id_jurusan'],
                'fakultas_id' => $value['id_fakultas'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn('id_jurusan');
        });
    }
};
