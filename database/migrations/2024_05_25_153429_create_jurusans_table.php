<?php

use App\Models\Jurusan;
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
        Schema::dropIfExists('jurusans');
        
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->integer('jurusan_id');
            $table->string('nama_jurusan_id')->nullable();
            $table->string('nama_jurusan_en')->nullable();
            $table->integer('id_fakultas');
            $table->timestamps();
        });

        $data = [['jurusan_id'=>101,'nama_jurusan_id'=>'AKUNTANSI','nama_jurusan_en'=>'ACCOUNTING','id_fakultas'=>1,],
                ['jurusan_id'=>102,'nama_jurusan_id'=>'EKONOMI PEMBANGUNAN','nama_jurusan_en'=>'DEVELOPMENT ECONOMICS','id_fakultas'=>1,],
                ['jurusan_id'=>103,'nama_jurusan_id'=>'MANAJEMEN','nama_jurusan_en'=>'MANAGEMENT','id_fakultas'=>1,],
                ['jurusan_id'=>201,'nama_jurusan_id'=>'ILMU HUKUM','nama_jurusan_en'=>'LEGAL STUDIES','id_fakultas'=>2,],
                ['jurusan_id'=>202,'nama_jurusan_id'=>'KENOTARIATAN','nama_jurusan_en'=>'NOTARY','id_fakultas'=>2,],
                ['jurusan_id'=>301,'nama_jurusan_id'=>'TEKNIK ELEKTRO ','nama_jurusan_en'=>'ELECTRICAL ENGINEERING ','id_fakultas'=>3,],
                ['jurusan_id'=>302,'nama_jurusan_id'=>'TEKNIK KIMIA ','nama_jurusan_en'=>'CHEMICAL ENGINEERING ','id_fakultas'=>3,],
                ['jurusan_id'=>303,'nama_jurusan_id'=>'TEKNIK MESIN ','nama_jurusan_en'=>'MECHANICAL ENGINEERING ','id_fakultas'=>3,],
                ['jurusan_id'=>304,'nama_jurusan_id'=>'TEKNIK PERTAMBANGAN DAN GEOLOGI ','nama_jurusan_en'=>'MINING AND GEOLOGICAL ENGINEERING ','id_fakultas'=>3,],
                ['jurusan_id'=>305,'nama_jurusan_id'=>'TEKNIK SIPIL DAN PERENCANAAN ','nama_jurusan_en'=>'CIVIL ENGINEERING AND PLANNING ','id_fakultas'=>3,],
                ['jurusan_id'=>306,'nama_jurusan_id'=>'ILMU TEKNIK','nama_jurusan_en'=>'ENGINEERING SCIENCE','id_fakultas'=>3,],
                ['jurusan_id'=>307,'nama_jurusan_id'=>'PROGRAM PROFESI INSINYUR','nama_jurusan_en'=>'PROFESSIONAL ENGINEER PROGRAM','id_fakultas'=>3,],
                ['jurusan_id'=>401,'nama_jurusan_id'=>'ANESTESIOLOGI DAN TERAPI INTENSIF','nama_jurusan_en'=>'ANESTHESIOLOGY AND INTENSIVE THERAPY','id_fakultas'=>4,],
                ['jurusan_id'=>402,'nama_jurusan_id'=>'BEDAH','nama_jurusan_en'=>'GENERAL SURGERY','id_fakultas'=>4,],
                ['jurusan_id'=>403,'nama_jurusan_id'=>'BEDAH TORAK KARDIAK DAN VASKULAR','nama_jurusan_en'=>'CARDIAC AND VASCULAR THORACY SURGERY','id_fakultas'=>4,],
                ['jurusan_id'=>404,'nama_jurusan_id'=>'DERMATOLOGI DAN VENEREOLOGI','nama_jurusan_en'=>'DERMATOLOGY AND VENEREOLOGY','id_fakultas'=>4,],
                ['jurusan_id'=>405,'nama_jurusan_id'=>'ILMU PENYAKIT DALAM','nama_jurusan_en'=>'INTERNAL MEDICINE','id_fakultas'=>4,],
                ['jurusan_id'=>406,'nama_jurusan_id'=>'KEDOKTERAN','nama_jurusan_en'=>'MEDICINE','id_fakultas'=>4,],
                ['jurusan_id'=>407,'nama_jurusan_id'=>'KEDOKTERAN GIGI','nama_jurusan_en'=>'DENTAL MEDICINE','id_fakultas'=>4,],
                ['jurusan_id'=>408,'nama_jurusan_id'=>'KEPERAWATAN','nama_jurusan_en'=>'NURSING','id_fakultas'=>4,],
                ['jurusan_id'=>409,'nama_jurusan_id'=>'KESEHATAN ANAK','nama_jurusan_en'=>'PEDIATRICS','id_fakultas'=>4,],
                ['jurusan_id'=>410,'nama_jurusan_id'=>'KESEHATAN MATA','nama_jurusan_en'=>'OPHTHALMOLOGY','id_fakultas'=>4,],
                ['jurusan_id'=>411,'nama_jurusan_id'=>'NEUROLOGI','nama_jurusan_en'=>'NEUROLOGY','id_fakultas'=>4,],
                ['jurusan_id'=>412,'nama_jurusan_id'=>'OBSTETRI DAN GINEKOLOGI','nama_jurusan_en'=>'OBSTETRICS AND GYNECOLOGY','id_fakultas'=>4,],
                ['jurusan_id'=>413,'nama_jurusan_id'=>'PATOLOGI ANATOMIK','nama_jurusan_en'=>'ANATOMIC PATHOLOGY','id_fakultas'=>4,],
                ['jurusan_id'=>414,'nama_jurusan_id'=>'PSIKOLOGI','nama_jurusan_en'=>'PSYCHOLOGY','id_fakultas'=>4,],
                ['jurusan_id'=>415,'nama_jurusan_id'=>'THT-KL','nama_jurusan_en'=>'OTORHINOLARINGOLOGY HEAD AND NECK SURGERY','id_fakultas'=>4,],
                ['jurusan_id'=>501,'nama_jurusan_id'=>'AGRIBISNIS','nama_jurusan_en'=>'AGRIBUSINESS','id_fakultas'=>5,],
                ['jurusan_id'=>502,'nama_jurusan_id'=>'BUDIDAYA PERTANIAN','nama_jurusan_en'=>'CROP SCIENCE','id_fakultas'=>5,],
                ['jurusan_id'=>503,'nama_jurusan_id'=>'HAMA DAN PENYAKIT TUMBUHAN','nama_jurusan_en'=>'PLANT PEST AND DISEASE','id_fakultas'=>5,],
                ['jurusan_id'=>504,'nama_jurusan_id'=>'ILMU TANAMAN','nama_jurusan_en'=>'PLANT SCIENCE','id_fakultas'=>5,],
                ['jurusan_id'=>506,'nama_jurusan_id'=>'ILMU-ILMU PERTANIAN','nama_jurusan_en'=>'AGRICULTURE SCIENCES','id_fakultas'=>5,],
                ['jurusan_id'=>507,'nama_jurusan_id'=>'PERIKANAN','nama_jurusan_en'=>'FISHERIES','id_fakultas'=>5,],
                ['jurusan_id'=>508,'nama_jurusan_id'=>'SOSIAL EKONOMI PERTANIAN','nama_jurusan_en'=>'AGRICULTURAL SOCIAL ECONOMICS','id_fakultas'=>5,],
                ['jurusan_id'=>509,'nama_jurusan_id'=>'TANAH','nama_jurusan_en'=>'SOIL','id_fakultas'=>5,],
                ['jurusan_id'=>510,'nama_jurusan_id'=>'TEKNOLOGI DAN INDUSTRI PETERNAKAN','nama_jurusan_en'=>'ANIMAL SCIENCE TECHNOLOGY AND INDUSTRY','id_fakultas'=>5,],
                ['jurusan_id'=>511,'nama_jurusan_id'=>'TEKNOLOGI PERTANIAN','nama_jurusan_en'=>'AGRICULTURAL TECHNOLOGY','id_fakultas'=>5,],
                ['jurusan_id'=>512,'nama_jurusan_id'=>'TEKNOLOGI INDUSTRI PERTANIAN','nama_jurusan_en'=>'AGRICULTURAL INDUSTRIAL TECHNOLOGY','id_fakultas'=>5,],
                ['jurusan_id'=>601,'nama_jurusan_id'=>'ILMU PENDIDIKAN','nama_jurusan_en'=>'EDUCATIONAL SCIENCE','id_fakultas'=>6,],
                ['jurusan_id'=>603,'nama_jurusan_id'=>'PENDIDIKAN ILMU PENGETAHUAN SOSIAL','nama_jurusan_en'=>'SOCIAL SCIENCE EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>604,'nama_jurusan_id'=>'PENDIDIKAN MATEMATIKA DAN ILMU PENGETAHUAN ALAM','nama_jurusan_en'=>'MATHEMATICS AND NATURAL SCIENCES EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>605,'nama_jurusan_id'=>'PENDIDIKAN PROFESI GURU','nama_jurusan_en'=>'TEACHER PROFESSIONAL EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>606,'nama_jurusan_id'=>'PENDIDIKAN LUAR SEKOLAH','nama_jurusan_en'=>'NON-FORMAL EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>607,'nama_jurusan_id'=>'PENDIDIKAN ANAK USIA DINI','nama_jurusan_en'=>'EARLY CHILDHOOD EDUCATION PROGRAMS','id_fakultas'=>6,],
                ['jurusan_id'=>608,'nama_jurusan_id'=>'PJJ PENDIDIKAN GURU SEKOLAH DASAR','nama_jurusan_en'=>'PJJ PRIMARY SCHOOL TEACHER EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>609,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN BAHASA INDONESIA','nama_jurusan_en'=>'PSKGJ INDONESIAN LANGUAGE EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>610,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN BAHASA INGGRIS','nama_jurusan_en'=>'PSKGJ ENGLISH LANGUAGE EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>611,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN BIOLOGI','nama_jurusan_en'=>'PSKGJ BIOLOGY EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>612,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN EKONOMI','nama_jurusan_en'=>'PSKGJ ECONOMIC EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>613,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN FISIKA','nama_jurusan_en'=>'PSKGJ PHYSICS EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>614,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN GURU SEKOLAH DASAR(PGSD)','nama_jurusan_en'=>'PSKGJ PRIMARY SCHOOL TEACHER EDUCATION (PGSD)','id_fakultas'=>6,],
                ['jurusan_id'=>615,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN KEWARGANEGARAAN','nama_jurusan_en'=>'PSKGJ CITIZENSHIP EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>616,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN KIMIA','nama_jurusan_en'=>'PSKGJ CHEMISTRY EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>617,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN MATEMATIKA','nama_jurusan_en'=>'PSKGJ MATHEMATICS EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>618,'nama_jurusan_id'=>'PSKGJ PENDIDIKAN SEJARAH','nama_jurusan_en'=>'PSKGJ HISTORY EDUCATION','id_fakultas'=>6,],
                ['jurusan_id'=>701,'nama_jurusan_id'=>'ILMU ADMINISTRASI PUBLIK','nama_jurusan_en'=>'PUBLIC ADMINISTRATION SCIENCE','id_fakultas'=>7,],
                ['jurusan_id'=>702,'nama_jurusan_id'=>'ILMU HUBUNGAN INTERNASIONAL','nama_jurusan_en'=>'INTERNATIONAL RELATIONS SCIENCE','id_fakultas'=>7,],
                ['jurusan_id'=>703,'nama_jurusan_id'=>'ILMU KOMUNIKASI','nama_jurusan_en'=>'COMMUNICATION SCIENCE','id_fakultas'=>7,],
                ['jurusan_id'=>704,'nama_jurusan_id'=>'SOSIOLOGI','nama_jurusan_en'=>'SOCIOLOGY','id_fakultas'=>7,],
                ['jurusan_id'=>801,'nama_jurusan_id'=>'BIOLOGI','nama_jurusan_en'=>'BIOLOGY','id_fakultas'=>8,],
                ['jurusan_id'=>802,'nama_jurusan_id'=>'FARMASI','nama_jurusan_en'=>'PHARMACY','id_fakultas'=>8,],
                ['jurusan_id'=>803,'nama_jurusan_id'=>'FISIKA','nama_jurusan_en'=>'PHYSICS','id_fakultas'=>8,],
                ['jurusan_id'=>804,'nama_jurusan_id'=>'ILMU KELAUTAN','nama_jurusan_en'=>'MARINE SCIENCE','id_fakultas'=>8,],
                ['jurusan_id'=>805,'nama_jurusan_id'=>'KIMIA','nama_jurusan_en'=>'CHEMISTRY','id_fakultas'=>8,],
                ['jurusan_id'=>806,'nama_jurusan_id'=>'MATEMATIKA','nama_jurusan_en'=>'MATHEMATICS','id_fakultas'=>8,],
                ['jurusan_id'=>807,'nama_jurusan_id'=>'ILMU MATEMATIKA DAN ILMU PENGETAHUAN ALAM','nama_jurusan_en'=>'MATHEMATICS AND NATURAL SCIENCES','id_fakultas'=>8,],
                ['jurusan_id'=>901,'nama_jurusan_id'=>'ILMU KOMPUTER','nama_jurusan_en'=>'COMPUTER SCIENCE','id_fakultas'=>9,],
                ['jurusan_id'=>902,'nama_jurusan_id'=>'SISTEM INFORMASI','nama_jurusan_en'=>'INFORMATION SYSTEM','id_fakultas'=>9,],
                ['jurusan_id'=>903,'nama_jurusan_id'=>'SISTEM KOMPUTER','nama_jurusan_en'=>'COMPUTER SYSTEM','id_fakultas'=>9,],
                ['jurusan_id'=>904,'nama_jurusan_id'=>'TEKNIK INFORMATIKA','nama_jurusan_en'=>'INFORMATICS ENGINEERING','id_fakultas'=>9,],
                ['jurusan_id'=>1001,'nama_jurusan_id'=>'GIZI','nama_jurusan_en'=>'NUTRITION','id_fakultas'=>10,],
                ['jurusan_id'=>1002,'nama_jurusan_id'=>'ILMU KESEHATAN MASYARAKAT','nama_jurusan_en'=>'PUBLIC HEALTH SCIENCE','id_fakultas'=>10,],
                ['jurusan_id'=>1003,'nama_jurusan_id'=>'KESEHATAN LINGKUNGAN','nama_jurusan_en'=>'ENVIRONMENTAL HEALTH','id_fakultas'=>10,],
                ['jurusan_id'=>1101,'nama_jurusan_id'=>'ILMU MATERIAL','nama_jurusan_en'=>'MATERIAL SCIENCE','id_fakultas'=>11,],
                ['jurusan_id'=>1102,'nama_jurusan_id'=>'KEPENDUDUKAN','nama_jurusan_en'=>'POPULATION','id_fakultas'=>11,],
                ['jurusan_id'=>1103,'nama_jurusan_id'=>'KESEHATAN LINGKUNGAN','nama_jurusan_en'=>'ENVIRONMENTAL HEALTH','id_fakultas'=>11,],
                ['jurusan_id'=>1104,'nama_jurusan_id'=>'PENGELOLAAN LINGKUNGAN','nama_jurusan_en'=>'MANAGEMENT OF THE ENVIRONMENT','id_fakultas'=>11,],
                ['jurusan_id'=>602,'nama_jurusan_id'=>'PENDIDIKAN BAHASA DAN SENI','nama_jurusan_en'=>'LANGUAGE Â AND ART EDUCATION','id_fakultas'=>6,],
            ];

            foreach ($data as $key => $value) {
                Jurusan::create($value);
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusans');
    }
};
