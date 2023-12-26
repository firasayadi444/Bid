<?php

namespace App\Entity;
use DateTimeImmutable;
use DateTime;
use App\Repository\BidRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: BidRepository::class)]

class Bid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

//    /**
//     * @Assert\LessThanOrEqual(propertyPath="prix_final", message="The piding price must be less than the final price.")
//     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4)]
    private ?string $bidingprice = null;

     /**
     * @param \DateTimeImmutable|null $date_deb
     */


    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $bidingdate = null;



    #[ORM\ManyToOne(inversedBy: 'bid')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBidingprice(): ?string
    {
        return $this->bidingprice;
    }

    public function setBidingprice(string $bidingprice): static
    {
        $this->bidingprice = $bidingprice;

        return $this;
    }
/**
     * @return DateTimeImmutable
     */
    public function getBidingdate(): ?DateTimeImmutable
    {
        return $this->bidingdate;
    }

    /**
     * @param \DateTimeImmutable|null $date_deb
     */
    public function setBidingdate(?DateTimeImmutable $bidingdate): void
    {
        $this->bidingdate = $bidingdate ?: new DateTimeImmutable();
    }



    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }
}
