<?php

namespace IMRIM\Bundle\LmsBundle;

use \Doctrine\Common\Collections\ArrayCollection;
use IMRIM\Bundle\LmsBundle\Entity\User;

/**
 * Description of UserManager
 *
 * @author johanna
 */
class UserManager {

    public function __construct($validator, $entityManager, $logger, $encoderFactory) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->encoderFactory = $encoderFactory;
    }
    
    /**
     * Generate a random string with $length alphanumeric characters. 
     * @param integer $length
     * @return string 
     */
    public static function generateRandomString($length=8)
    {
        $result = "";
        $chars = 'abcdefghijklmnopqrstuvxyz0123456789';
        for ($i=0;$i<$length;$i++){
            $result = $result.$chars[mt_rand(0,strlen($chars)-1)];
        }
        return $result;
    }

    /**
     * Return the links between User CSV fields and user's attributes
     * @return Array()
     */
    public static function getCsvMapping() {
        return array(
            'Username' => 'login',
            'First Name' => 'firstName',
            'Last Name' => 'lastName',
            'Email' => 'mail',
        );
    }

    /**
     * Return the name of the associated role class
     * @param string $role
     * @return string
     * @throws \NonLoadableClass 
     */
    private static function getRoleClassName($role) {
        $base = "\\IMRIM\\Bundle\\LmsBundle\\Entity\\";
        $className = $base . ucfirst($role) . 'Role';
        if (class_exists($className)) {
            return $className;
        } else {
            throw new \Exception('Unknown role ' . $role);
        }
    }

    /**
     * Take a CSV file name and create users with it. Create a CSV with corresponding password in IMRIMLmsBundle::Ressources/doc/ directory
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function csvUserImport($fileName, $roleName, $force=false) {
        $delim = ','; // CSV delimiter
        $map = UserManager::getCsvMapping();
        $em = $this->entityManager;
	$logger = $this->logger;
        $encoderFactory = $this->encoderFactory;

        $validDataCollection = new ArrayCollection();
        $nonValidDataCollection = new ArrayCollection();
        $passwordArray = Array();

        try {
            $roleClass = UserManager::getRoleClassName($roleName);
        } catch (\Exception $e) {
            $logger->err('Role : ' . $roleName . ' is unknown!');
            return -1;
        }
        if (!file_exists($fileName)) {
            $logger->err('File not found: ' . $fileName);
            return -1;
        }

        // Write some informations in the console
        $logger->info('Using file: ' . $fileName);

        // If the file can be opened in read mode
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            // CSV parser (read the first line)
            $head = fgetcsv($handle, 0, $delim);

            // For each other line of the CSV file
            while (($data = fgetcsv($handle, 0, $delim)) !== FALSE) {
                $user = new User();
                foreach ($data as $key => $value) {
                    $field = $map[$head[$key]]; // get the associated field in the first line
                    $value = $data[$key]; // get the value of the field
                    $setter = 'set' . ucfirst($field);
                    $user->$setter($value);
                }

                // Check if the user is valid
                $errors = $this->validator->validate($user);

                if (count($errors) > 0) {
                    foreach ($errors as $err) {
                        $logger->err($user->getLogin() . '::' . $err->getPropertyPath() . ': ' . $err->getMessage() . ' (' . $err->getInvalidValue() . ')');
                        $nonValidDataCollection->add($user);
                    }
                } else {
                    $homonymousUser = $em->getRepository('IMRIMLmsBundle:User')->findOneByLogin($user->getLogin());

                    if ($homonymousUser != null) {
                        $logger->err('User ' . $user->getLogin() . ' already exists.');
                        $nonValidDataCollection->add($user);
                    } else {
                        $logger->info('create ' . $user->getLogin());

                        // add the correct role to the user
                        $roleSetter = 'set' . ucfirst($roleName) . 'Role';
                        $user->$roleSetter(new $roleClass());
                        // If no error and --force option, persist the user
                        if ($force) {

                            // Generates a random password and salt
                            $password = UserManager::generateRandomString();

                            $user->setSalt(md5(UserManager::generateRandomString()));

                            // Encode the password
                            $encoder = $encoderFactory->getEncoder($user);
                            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                            $em->persist($user);
                            $em->flush();
                            $passwordArray[$user->getId()] = $password;
                            $logger->info('id : ' . $user->getId());
                        }

                        // Add the user to the valid data collection
                        $validDataCollection->add($user);
                    }
                }
            }
        } else {
            $logger->err('File error');
        }

        // if persist data write a csv
        if ($force) {
            $outFileName = 'user_' . $roleName . '_' . date('Y-m-d') . '_' . time() . '.csv';
            $filePath = dirname(__FILE__) . '/Resources/doc/' . $outFileName;
            $outFile = fopen($filePath, 'w');
            $logger->info('Writing CSV to ' . $filePath);

            // print the csv header
            $head = array();
            foreach ($map as $csvField => $field) {
                $head[] = $csvField;
            }
            $head[] = 'Mot de passe';
            $head[] = 'id';
            fputcsv($outFile, $head);

            // print each valid data to csv
            foreach ($validDataCollection as $user) {
                $data = array();
                foreach ($map as $field) {
                    $getter = 'get' . ucfirst($field);
                    $data[] = $user->$getter();
                }
                $data[] = $passwordArray[$user->getId()];
                $data[] = $user->getId();

                fputcsv($outFile, $data);
            }
            fclose($outFile);
        }
    }

}
