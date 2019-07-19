<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{
    private $passwordEncoder;
    private static $language = ["fr", "en", "es"];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(3, 'main_users_admin', function($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('admin_%d@gmail.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setLastname($this->faker->lastName);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setLanguage($this->faker->randomElement(self::$language));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '12345678'
            ));
            $user->setActif($this->faker->boolean);
            return $user;
        });
        $this->createMany(10, 'main_users_manager', function($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('manager_%d@gmail.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setLastname($this->faker->lastName);
            $user->setRoles(['ROLE_MANAGER']);
            $user->setLanguage($this->faker->randomElement(self::$language));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '12345678'
            ));
            $user->setActif($this->faker->boolean);
            return $user;
        });
        $this->createMany(10, 'admin_users_conducteur', function($i) {
            $user = new User();
            $user->setEmail(sprintf('conducteur_%d@gmail.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setLastname($this->faker->lastName);
            $user->setRoles(['ROLE_CONDUCTEUR']);
            $user->setLanguage($this->faker->randomElement(self::$language));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '12345678'
            ));
            $user->setActif($this->faker->boolean);
            return $user;
        });
        $manager->flush();
    }
}
