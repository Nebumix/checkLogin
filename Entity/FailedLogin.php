<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FailedLogin
 *
 * @ORM\Table(name="failed_login")
 * @ORM\Entity
 */
class FailedLogin
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     */
    protected $idUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     *
     * @ORM\OneToOne(targetEntity="\Acme\UserBundle\Entity\User", inversedBy="failedLogin")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return FailedLogin
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return FailedLogin
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     * @return FailedLogin
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set user
     *
     * @param \Acme\UserBundle\Entity\User $user
     * @return FailedLogin
     */
    public function setUser(\Acme\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Acme\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
