<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Region;
use App\Entity\Annonce;
use App\Entity\Message;
use App\Entity\Service;
use App\Entity\Department;
use Doctrine\DBAL\Connection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\Provider\PermutationProvider;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Connection $connection)
    {
        $this->encoder = $passwordEncoder;
        // On truncate les données
        $this->truncate($connection);
    }

    public function truncate(Connection $connection)
    {
        // Désactive la vérification des contraintes FK
        $connection->query('SET foreign_key_checks = 0');
        // On tronque
        $connection->query('TRUNCATE grade');
        $connection->query('TRUNCATE service');
        $connection->query('TRUNCATE user_role');
        $connection->query('TRUNCATE city');
        $connection->query('TRUNCATE user');
    }

    public function load(ObjectManager $manager)
    {
        // On instancie Faker
        $faker = \Faker\Factory::create('fr_FR');
        // On ajoute notre Provider à Faker
        $faker->addProvider(new PermutationProvider($faker));
        // Permet d'avoir toujours les mêmes données
        $faker->seed('hasard');
        
        // 13 grades
        $gradesList = [];
        for ($i = 1; $i <= 13; $i++) {
            $grade = new Grade();
            $grade->setName($faker->unique()->permutationGrade);
            $manager->persist($grade);

            $gradesList[] = $grade;
        }

        // 30 Services
        $servicesList = [];
        for ($i = 1; $i <= 30; $i++) {
            $service = new Service();
            $service->setName($faker->unique()->permutationService);
            $manager->persist($service);

            $servicesList[] = $service;
        }

        // 18 régions
        $regionsList = [];
        for ($i = 1; $i <= 18; $i++) {
            $region = new Region();
            $regionInfos = $faker->unique()->permutationRegion;
            $region->setName($regionInfos['name']);
            $region->setCode((int)$regionInfos['code']);
            $manager->persist($region);

            $regionsList[] = $region;
        }

        // 102 départements
        $departmentsList=[];
        for ($i = 1; $i <= 101; $i++) {
            $department = new Department();
            $departmentInfos = $faker->unique()->permutationDepartment;
            $department->setName($departmentInfos['name'])
                ->setCode((int)$departmentInfos['code']);
                // On cherche la région qui correspond à celle du départment
                foreach($regionsList as $region) {
                    if($region->getCode() == $departmentInfos['codeRegion']) {
                        $department->setRegion($region);
                    }
                }
            $manager->persist($department);

            $departmentsList[] = $department;
        }

        // 20 villes
        $citiesList = [];
        for ($i = 1; $i <= 20; $i++) {
            $city = new City();
            $cityInfos = $faker->unique()->permutationCity;
            $city->setName($cityInfos['name']);
            $city->setCodeINSEE($cityInfos['codeINSEE']);
            // On recupère le département associé à la ville
            foreach ($departmentsList as $department) {
                if ($department->getCode() == $cityInfos['codeDepartement']) {
                    $city->setDepartment($department);
                }
            }
            $city->setLatitude((float)$cityInfos['latitude']);
            $city->setLongitude((float)$cityInfos['longitude']);
            $manager->persist($city);

            $citiesList[] = $city;
        }

        // Roles
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $roleUser->setLabel('Utilisateur');
        $manager->persist($roleUser);

        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setLabel('Administrateur');
        $manager->persist($roleAdmin);

        // User
        $usersList = [];
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email);
            $firstname = $faker->unique()->firstName;
            dump($firstname);
            $user->setPseudo($firstname);
            $user->setFirstname($firstname);
            $user->setLastname($faker->unique()->lastName);
            $user->setPassword($this->encoder->encodePassword($user, 'user'));
            $user->addUserRole($roleUser);
            $manager->persist($user);

            $usersList[] = $user;
        }
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setPseudo('admin');
        $admin->setFirstname('admin');
        $admin->setLastname('admin');
        $admin->setPassword($this->encoder->encodePassword($admin, 'admin'));
        $admin->addUserRole($roleAdmin);
        $manager->persist($admin);

        // 20 Annonce
        $annoncesList = [];
        for ($i = 1; $i <= 20; $i++) {
            $annonce = new Annonce();
            $annonce->setInformations($faker->permutationInformation[$i - 1]);
            // On ajoute un user en vérifiant qu'il n'est pas déjà utilisé
            $annonce->setUser($usersList[$i-1]);

            // On ajoute un grade
            $annonce->setGrade($gradesList[array_rand($gradesList)]);
            // On ajoute un service
            $annonce->setService($servicesList[array_rand($servicesList)]);
            // On ajoute la ville actuelle
            $city = $citiesList[array_rand($citiesList)];
            $annonce->setCity($city);

            // On ajoute la ville de mutation
            $randomCity = $citiesList[array_rand($citiesList)];
            $annonce->setMutationCity($randomCity);
            $annonce->setMutationDepartment($randomCity->getDepartment());
            $annonce->setMutationRegion($randomCity->getDepartment()->getRegion());
            
            $annonce->setCreatedAt($faker->dateTimeInInterval($startDate = 'now', $interval = '- 3 months', $timezone = null));
            $manager->persist($annonce);

            $annoncesList[] = $annonce;
        }

        // MESSAGES
        $messagesList = [];
        for ($i = 1; $i <= 10; $i++) {
            $message = new Message();
            $message->setContent($faker->text($maxNbChars = 50));
            if ($i % 2 == 0) {
                $message->setAuthor($usersList[0]);
                $message->setRecipient($usersList[1]);
            } else {
                $message->setAuthor($usersList[1]);
                $message->setRecipient($usersList[0]);
                $message->setCreatedAt(new DateTime());
            }

            $date = new DateTime();
            $date = $date->modify('+'.$i.' minute');
            $message->setCreatedAt($date);

            $manager->persist($message);

            $messagesList[] = $message;
        }

        

        $manager->flush();
    }
}
