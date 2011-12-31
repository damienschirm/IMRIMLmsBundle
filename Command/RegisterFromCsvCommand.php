<?php

namespace IMRIM\Bundle\LmsBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Doctrine\Common\Collections\ArrayCollection;
use IMRIM\Bundle\LmsBundle\Entity\User;
use IMRIM\Bundle\LmsBundle\LmsToolbox;

class RegisterFromCsvCommand extends ContainerAwareCommand {

    private $delim = ','; // CSV delimiter

    /**
     * Defines the command behaviour. 
     */

    protected function configure() {
        parent::configure();
        $this->setName('register:csv') // command : app/console register:csv
                ->setDescription('import csv file')
                ->addArgument('file', InputArgument::REQUIRED, 'CSV File to parsing')
                ->addArgument('role', InputArgument::REQUIRED, 'Role of the user to create')
                ->addOption('force');
    }

    /**
     * Return the links between CSV fields and user's attributes
     * @return Array()
     */
    public function getMapping() {
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
    private function getRoleClassName($role) {
        $base = "\\IMRIM\\Bundle\\LmsBundle\\Entity\\";
        $className = $base . ucfirst($role) . 'Role';
        if (class_exists($className)) {
            return $className;
        } else {
            throw new \Exception();
        }
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException when this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $fileName = $input->getArgument('file');
        $delim = $this->delim; // CSV delimiter
        $map = $this->getMapping();
        $validator = $this->getContainer()->get('validator');

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $validDataCollection = new ArrayCollection();
        $nonValidDataCollection = new ArrayCollection();
        $passwordArray = Array();

        $roleName = $input->getArgument('role');
        try {
            $roleClass = $this->getRoleClassName($roleName);
        } catch (\Exception $e) {
            $output->writeln('<error>Role : ' . $roleName . ' is unknown!</error>');
            return -1;
        }
        if (!file_exists($fileName)) {
            $output->writeln('<error>File not found: ' . $fileName . '</error>');
            return -1;
        }

        // Write some informations in the console
        $output->writeln('<info>using file: ' . $fileName . '</info>');
        $output->writeln('');

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
                $errors = $validator->validate($user);

                if (count($errors) > 0) {
                    foreach ($errors as $err) {
                        $output->writeln('<error>' . $user->getLogin() . '::' . $err->getPropertyPath() . ': ' . $err->getMessage() . ' (' . $err->getInvalidValue() . ')</error>');
                        $nonValidDataCollection->add($user);
                    }
                } else {
                    $homonymousUser = $em->getRepository('IMRIMLmsBundle:User')->findOneByLogin($user->getLogin());

                    if ($homonymousUser != null) {
                        $output->writeln('<error> User ' . $user->getLogin() . ' already exists. </error>');
                        $nonValidDataCollection->add($user);
                    } else {
                        $output->writeln('<info>create ' . $user->getLogin() . '</info>');

                        // add the correct role to the user
                        $roleSetter = 'set' . ucfirst($roleName) . 'Role';
                        $user->$roleSetter(new $roleClass());
                        // If no error and --force option, persist the user
                        if ($input->getOption('force')) {

                            // Generates a random password and salt
                            $password = LmsToolbox::generateRandomString();

                            $user->setSalt(md5(LmsToolbox::generateRandomString()));

                            // Encode the password
                            $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($user);
                            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                            $em->persist($user);
                            $em->flush();
                            $passwordArray[$user->getId()] = $password;
                            $output->writeln('<info>id : ' . $user->getId() . '</info>');
                        }

                        // Add the user to the valid data collection
                        $validDataCollection->add($user);
                        $output->writeln('');
                    }
                }
            }
        } else {
            $output->writeln('<error>File error</error>');
        }

        // if persist data write a csv
        if ($input->getOption('force')) {
            $outFileName = 'user_' . $roleName . '_' . date('Y-m-d') . '_' . time() . '.csv';
            $filePath = dirname(__FILE__) . '/../Resources/doc/' . $outFileName;
            $outFile = fopen($filePath, 'w');
            $output->writeln('<info>Writing CSV to ' . $filePath . '</info>');

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