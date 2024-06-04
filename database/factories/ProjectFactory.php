<?php
namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $categories = ['thesis', 'dissertation', 'capstone', 'feasibility study'];
        // $departments = ['CCS', 'CBA', 'CEAS', 'CHTM', 'CAHS'];
        // $courses = ['BSIT', 'BSCS',  'BSEMC', 'ACT', 'BSA', 'BSCA', 'BSBA-FM', 'BSBA-HRM', 'BSBA-MKT', 'BSN', 'BSM', 'GM', 'BSHM', 'BSTM', 'BEEd', 'BECEd', 'BSEd-E', 'BSEd-FIL', 'BSEd-M', 'BSEd-SCI', 'BSEd-SOC', 'BPEd', 'BCAEd', 'BACOM', 'TCP'];
        // $languages = ['FIL', 'FOR'];

        do {
            // Generate a random string
            $accession = Str::random(5);
        } while (Project::where('accession', $accession)->exists());

        $authors = [ 
            fake()->name(),
            fake()->name(),
            fake()->name()
        ];

        $keywords = [ 
            fake()->word(),
            fake()->word(),
            fake()->word()
        ];

        return [
            'accession' => $accession,
            'authors' => json_encode($authors),
            'title' => Str::title(fake()->words(3, true)), 
            'date_published' => fake()->date(), 
            'language' => fake()->randomElement(['FIL', 'FOR']),
            'abstract' => fake()->sentence(20, true),
            'keywords' => json_encode($keywords)
        ];
    }
}
