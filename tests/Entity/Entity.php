<?php declare(strict_types=1);

namespace DataGridTests\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entity
 *
 * @package DataGridTests\Entity
 *
 * @ORM\Entity
 */
final class Entity
{

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="`string`")
     */
    private string $string;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="`int`")
     */
    private int $int;

    /**
     * @var float
     *
     * @ORM\Column(type="float", name="`float`")
     */
    private float $float;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="`bool`")
     */
    private bool $bool;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", name="`date`")
     */
    private DateTime $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @param string $string
     *
     * @return Entity
     */
    public function setString(string $string): Entity
    {
        $this->string = $string;

        return $this;
    }

    /**
     * @return int
     */
    public function getInt(): int
    {
        return $this->int;
    }

    /**
     * @param int $int
     *
     * @return Entity
     */
    public function setInt(int $int): Entity
    {
        $this->int = $int;

        return $this;
    }

    /**
     * @return float
     */
    public function getFloat(): float
    {
        return $this->float;
    }

    /**
     * @param float $float
     *
     * @return Entity
     */
    public function setFloat(float $float): Entity
    {
        $this->float = $float;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->bool;
    }

    /**
     * @param bool $bool
     *
     * @return Entity
     */
    public function setBool(bool $bool): Entity
    {
        $this->bool = $bool;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     *
     * @return Entity
     */
    public function setDate(DateTime $date): Entity
    {
        $this->date = $date;

        return $this;
    }

}
