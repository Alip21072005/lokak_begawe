<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterSkillSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Menyiapkan Bank Data Master Skills...');

        $skills = [
            // --- IT, Software Development & Data ---
            'PHP',
            'Laravel',
            'CodeIgniter',
            'JavaScript',
            'TypeScript',
            'Vue.js',
            'React.js',
            'Next.js',
            'Node.js',
            'Express.js',
            'Python',
            'Django',
            'Flask',
            'Java',
            'Spring Boot',
            'C#',
            '.NET',
            'C++',
            'Go (Golang)',
            'Ruby',
            'Ruby on Rails',
            'Swift',
            'Kotlin',
            'Flutter',
            'React Native',
            'Dart',
            'HTML',
            'CSS',
            'SASS/SCSS',
            'Tailwind CSS',
            'Bootstrap',
            'SQL',
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Redis',
            'Oracle DB',
            'Git',
            'GitHub',
            'GitLab',
            'Docker',
            'Kubernetes',
            'AWS',
            'Google Cloud Platform (GCP)',
            'Microsoft Azure',
            'Linux Server Administration',
            'RESTful API',
            'GraphQL',
            'Microservices',
            'Unit Testing',
            'CI/CD',
            'Penetration Testing',
            'Cybersecurity',
            'Ethical Hacking',
            'Machine Learning',
            'Artificial Intelligence (AI)',
            'Data Analysis',
            'Data Science',
            'Data Engineering',
            'Tableau',
            'Power BI',
            'Hadoop',
            'Apache Spark',

            // --- Desain, UI/UX & Multimedia ---
            'UI/UX Design',
            'Figma',
            'Adobe XD',
            'Sketch',
            'Wireframing',
            'Prototyping',
            'User Research',
            'Graphic Design',
            'Adobe Photoshop',
            'Adobe Illustrator',
            'CorelDRAW',
            'InDesign',
            'Video Editing',
            'Adobe Premiere Pro',
            'After Effects',
            'Final Cut Pro',
            'DaVinci Resolve',
            'Animation',
            '3D Modeling',
            'Blender',
            'AutoCAD',
            'Typography',
            'Photography',
            'Videography',

            // --- Pemasaran, Sales & Komunikasi ---
            'Digital Marketing',
            'Search Engine Optimization (SEO)',
            'Search Engine Marketing (SEM)',
            'Social Media Marketing',
            'Social Media Management',
            'Content Creation',
            'Content Writing',
            'Copywriting',
            'Email Marketing',
            'Google Ads',
            'Facebook/Meta Ads',
            'TikTok Ads',
            'Public Relations',
            'Brand Management',
            'Market Research',
            'B2B Sales',
            'B2C Sales',
            'Customer Relationship Management (CRM)',
            'Telemarketing',
            'Negotiation',
            'Lead Generation',

            // --- Akuntansi, Keuangan & Bisnis ---
            'Accounting',
            'Bookkeeping',
            'Financial Analysis',
            'Financial Modeling',
            'Tax Preparation',
            'Auditing',
            'Payroll Management',
            'MYOB',
            'Accurate',
            'Xero',
            'Zahir Accounting',
            'SAP',
            'ERP Systems',
            'Business Strategy',
            'Business Development',
            'Risk Management',

            // --- Administrasi, HRD & Operasional ---
            'Human Resources (HR)',
            'Talent Acquisition',
            'Recruitment',
            'Employee Relations',
            'Performance Management',
            'Training and Development',
            'Data Entry',
            'Microsoft Office',
            'Microsoft Excel (Advanced)',
            'Microsoft Word',
            'Microsoft PowerPoint',
            'Google Workspace',
            'Administration',
            'Secretarial Skills',
            'Project Management',
            'Agile Methodology',
            'Scrum',
            'Supply Chain Management',
            'Logistics',
            'Inventory Management',

            // --- Teknik, Konstruksi & Sektor Primer ---
            'Civil Engineering',
            'Mechanical Engineering',
            'Electrical Engineering',
            'Architecture',
            'Quality Control (QC)',
            'Quality Assurance (QA)',
            'Health, Safety, and Environment (HSE) / K3',
            'Surveying',
            'Heavy Equipment Operation',
            'Welding',
            'Agronomy',
            'Plantation Management',

            // --- Soft Skills ---
            'Communication',
            'Leadership',
            'Problem Solving',
            'Teamwork',
            'Time Management',
            'Critical Thinking',
            'Adaptability',
            'Creativity',
            'Work Ethic',
            'Emotional Intelligence',
            'Conflict Resolution',
            'Decision Making',
            'Public Speaking',
            'Presentation Skills',
            'Mentoring',
            'Multitasking',
            'Detail Oriented',

            // --- Bahasa Asing ---
            'English Proficiency',
            'Mandarin (Chinese)',
            'Japanese',
            'Korean',
            'Arabic',
            'German',
            'French'
        ];

        // Hapus duplikasi jika ada di dalam array (untuk berjaga-jaga)
        $uniqueSkills = array_unique($skills);
        $insertedCount = 0;

        foreach ($uniqueSkills as $skill) {
            $exists = DB::table('master_skills')->where('nama_skill', $skill)->exists();
            if (!$exists) {
                DB::table('master_skills')->insert([
                    'id' => (string) Str::uuid(),
                    'nama_skill' => $skill,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $insertedCount++;
            }
        }

        $this->command->info("Berhasil menambahkan {$insertedCount} skill baru ke database!");
    }
}