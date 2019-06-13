<?php

namespace App\DataFixtures;

use App\Entity\Survey;
use App\Entity\SurveyCategory;
use App\Entity\SurveyQuestion;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SurveyFixtures extends BaseFixture implements DependentFixtureInterface
{
    /**
     * Amount of categories inside each survey.
     */
    const COUNT_CATEGORIES = 2;

    /**
     * Amount of questions inside each category.
     */
    const COUNT_QUESTIONS = 2;

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_survey', function () use ($manager) {

            $team = $this->faker->randomElement(['team', 'manager', 'no']);

            $survey = (new Survey())
                ->setTitle($this->faker->sentence)
                ->setDirection($this->getRandomReference('main_direction'))
                ->setTeam($team)
                ->setCountTeam($team === 'team' ? $this->faker->numberBetween(1, 3) : null)
                ->setBestPracticeLabel($this->faker->sentence)
                ->setBestPracticeHelp($this->faker->sentence)
                ->setUpdatedAt(new \DateTime())
            ;

            for ($i = 0; $i < self::COUNT_CATEGORIES; $i++) {

                $category = (new SurveyCategory())
                    ->setTitle($this->faker->sentence)
                    ->setUpdatedAt(new \DateTime())
                ;

                for ($j = 0; $j < self::COUNT_QUESTIONS; $j++) {
                    $category->addQuestion(
                        (new SurveyQuestion())
                            ->setLabel($this->faker->sentence)
                            ->setHelp($this->faker->sentence)
                    );
                }

                $survey->addCategory($category);
            }

            return $survey;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DirectionFixtures::class,
        ];
    }
}
