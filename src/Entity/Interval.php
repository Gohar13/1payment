<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\IntervalInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="range_interval",
 *     indexes={
 *         @ORM\Index(name="min_idx", columns={"min"}),
 *         @ORM\Index(name="max_idx", columns={"max"})
 *     }
 * )
 */
class Interval implements IntervalInterface
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     */
    private int $min;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     */
    private int $max;

    public function getId(): int
    {
        if ($this->id === null) {
            throw new \LogicException(
                sprintf(
                    'Can not get "id" from %s. Entity is not persisted yet',
                    get_class($this)
                )
            );
        }

        return $this->id;
    }

    public function setMin(int $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function setMax(int $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }
}