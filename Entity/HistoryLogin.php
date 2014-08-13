<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryLogin
 *
 * @ORM\Table(name="history_login")
 * @ORM\Entity
 */
class HistoryLogin
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     *
     */
    protected $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="success", type="boolean")
     */
    private $success;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\Column(name="ip", type="string", length=30)
     *
     */
    protected $ip;

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
     * Set success
     *
     * @param boolean $success
     * @return HistoryLogin
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Get success
     *
     * @return boolean 
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return HistoryLogin
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
     * Set reason
     *
     * @param string $reason
     * @return HistoryLogin
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }



    /**
     * Set email
     *
     * @param string $email
     * @return HistoryLogin
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return HistoryLogin
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }
}
