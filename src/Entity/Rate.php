<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rate
 *
 * @ORM\Table(name="rate", uniqueConstraints={@ORM\UniqueConstraint(name="rate_base_quote_provider_uindex", columns={"base", "quote", "provider"})})
 * @ORM\Entity
 *
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get", "put"={"normalization_context"={"groups"={"put"}}}, "delete"},
 *     paginationEnabled=false,
 * )
 */
class Rate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups("put")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="base", type="string", length=3, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=3)
     *
     * @Groups("put")
     */
    private $base;

    /**
     * @var string
     *
     * @ORM\Column(name="quote", type="string", length=3, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=3)
     *
     * @Groups("put")
     */
    private $quote;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=20, scale=8, nullable=false)
     *
     * @Assert\PositiveOrZero()
     *
     * @Groups("put")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=12, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=12)
     *
     * @Groups("put")
     */
    private $provider;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updated;

    public function __construct()
    {
        $this->created = $this->updated = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }


}
