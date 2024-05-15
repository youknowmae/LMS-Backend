<?php
namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $categories = ['thesis', 'dissertation', 'capstone', 'feasibility study'];
        $departments = ['CCS', 'CBA', 'CEAS', 'CHTM', 'CAHS'];
        $courses = ['BSIT', 'BSCS',  'BSEMC', 'ACT', 'BSA', 'BSCA', 'BSBA-FM', 'BSBA-HRM', 'BSBA-MKT', 'BSN', 'BSM', 'GM', 'BSHM', 'BSTM', 'BEEd', 'BECEd', 'BSEd-E', 'BSEd-FIL', 'BSEd-M', 'BSEd-SCI', 'BSEd-SOC', 'BPEd', 'BCAEd', 'BACOM', 'TCP'];
        $languages = ['FIL', 'FOR'];

        return [
            'category' => $this->faker->randomElement($categories),
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name(),
            'department' => $this->faker->randomElement($departments),
            'course' => $this->faker->randomElement($courses),
            'image_location' => $this->faker->imageUrl(), // Assuming you want to generate a URL for the image
            'date_published' => $this->faker->date(),
            'language' => $this->faker->randomElement($languages),
            'abstract' => $this->faker->sentence(20, true),
        ];
    }
}
