<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{
    private $passwordEncoder;
    private static $language = ["fr"];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(1, 'main_users_admin', function($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('admin_%d@gmail.com', $i));
            $user->setFirstName("Sebastien");
            $user->setLastname("Faye");
            $user->setRoles(['ROLE_ADMIN']);
            $user->setLanguage($this->faker->randomElement(self::$language));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '12345678'
            ));
            $user->setCompletedProfile(false);
            $user->setActif("true");
            return $user;
        });
        $manager->flush();
    }
}
