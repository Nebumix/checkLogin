<?php

namespace Acme\DemoBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;


use Acme\DemoBundle\Entity\FailedLogin;
use Acme\DemoBundle\Entity\HistoryLogin;

use Doctrine\ORM\EntityManager;


class AuthyAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoderFactory;

    private $em;

    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $em)
    {
        $this->encoderFactory = $encoderFactory;

        $this->em = $em;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Invalid username or password');
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $passwordValid = $encoder->isPasswordValid(
            $user->getPassword(),
            $token->getCredentials(),
            $user->getSalt()
        );

        if ($passwordValid) {

            $flog = $user->getFailedLogin();
            if($flog->getNumber() >= 5){

                $hlog = new HistoryLogin();
                $hlog->setSuccess(0);
                $hlog->setDate(new \DateTime('now'));
                $hlog->setReason('You have wrong the login more than 5 times. Your account is blocked, Please call the assistance.');
                $hlog->setEmail($token->getUser());

                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }

                $hlog->setIp($ip);

                $this->em->persist($hlog);

                $this->em->flush();


                throw new AuthenticationException(
                    'You have wrong the login more than 5 times. Your account is blocked, Please call the assistance.',
                    100
                ); 

            }


            //EXAMPLE to role to add
            /*if (in_array('ROLE_CLIENT', $user->getRoles())) {
                if ($user->getData()->getTerms() != 1) {

                    $hlog = new HistoryLogin();
                    $hlog->setSuccess(0);
                    $hlog->setDate(new \DateTime('now'));
                    $hlog->setReason('Before to access you need to accept terms and conditions.');
                    $hlog->setEmail($token->getUser());

                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    }

                    $hlog->setIp($ip);

                    $this->em->persist($hlog);

                    $this->em->flush();


                    throw new AuthenticationException(
                        'Before to access you need to accept terms and conditions.',
                        100
                    ); 
                }
            }*/


            $flog->setDate(new \DateTime('now'));
            $flog->setNumber(0);



            $hlog = new HistoryLogin();
            $hlog->setSuccess(1);
            $hlog->setDate(new \DateTime('now'));
            $hlog->setReason('');
            $hlog->setEmail($token->getUser());

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $hlog->setIp($ip);

            $this->em->persist($hlog);
            $this->em->persist($flog);

            $this->em->flush();


            return new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                $providerKey,
                $user->getRoles()
            );
        }


        $repository = $this->em->getRepository('AcmeUserBundle:User');

        $query = $repository->createQueryBuilder('u')
            ->where('u.username = :username')
            //->orWhere('u.email = :username')
            ->setParameter('username', $token->getUser())
            ->getQuery();

        $us = $query->getSingleResult();

        if($us){

            $flog = $us->getFailedLogin();
            $flog->setNumber($flog->getNumber() + 1);
            $flog->setDate(new \DateTime('now'));

            if($flog->getNumber() >= 5){
                $us->setEnabled(0);
            }

            $hlog = new HistoryLogin();
            $hlog->setSuccess(0);
            $hlog->setDate(new \DateTime('now'));
            $hlog->setReason('Invalid username or password');
            $hlog->setEmail($token->getUser());

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $hlog->setIp($ip);


            $this->em->persist($us);
            $this->em->persist($flog);
            $this->em->persist($hlog);


            $this->em->flush();

        }



        throw new AuthenticationException('Invalid username or password');
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}