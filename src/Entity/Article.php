<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;
    #[ORM\Column(type: Types::DATE_MUTABLE,nullable: true)]
    private ?\DateTime $date_deb= null ;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_fin = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4)]
    private ?string $prix_depart = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4, nullable: true)]
    private ?string $prix_final = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4, nullable: true)]
    private ?string $prixEnchereMax = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $biddingPrices = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4, nullable: true)]
    private ?string $winningbidingprice = null;

    #[ORM\ManyToOne(inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Bid::class)]
    private Collection $bids;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTime $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getPrixDepart(): ?string
    {
        return $this->prix_depart;
    }

    public function setPrixDepart(string $prix_depart): static
    {
        $this->prix_depart = $prix_depart;

        return $this;
    }

    public function getPrixFinal(): ?string
    {
        return $this->prix_final;
    }

    public function setPrixFinal(?string $prix_final): static
    {
        $this->prix_final = $prix_final;

        return $this;
    }

    public function getPrixEnchereMax(): ?string
    {
        return $this->prixEnchereMax;
    }

    public function setPrixEnchereMax(?string $prixEnchereMax): static
    {
        $this->prixEnchereMax = $prixEnchereMax;

        return $this;
    }

    public function getBiddingPrices(): ?array
    {
        return $this->biddingPrices;
    }

    public function setBiddingPrices(?array $biddingPrices): static
    {
        $this->biddingPrices = $biddingPrices;

        return $this;
    }

    public function getWinningbidingprice(): ?string
    {
        return $this->winningbidingprice;
    }

    public function setWinningbidingprice(?string $winningbidingprice): static
    {
        $this->winningbidingprice = $winningbidingprice;

        return $this;
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

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

//    public function addBid(Bid $bid): static
//    {
//        if (!$this->bids->contains($bid)) {
//            $this->bids->add($bid);
//            $bid->setArticle($this);
//        }
//
//        return $this;
//    }
//
//    public function removeBid(Bid $bid): static
//    {
//        if ($this->bids->removeElement($bid)) {
//            // set the owning side to null (unless already changed)
//            if ($bid->getArticle() === $this) {
//                $bid->setArticle(null);
//            }
//        }
//
//        return $this;
//    }

    /**
     * @return DateTime
     */
    public function getDateDeb(): DateTime
    {
        return $this->date_deb;
    }

    /**
     * @param \DateTime|null $date_deb
     */
    public function setDateDeb(?\DateTime $date_deb): void
    {
        $this->date_deb = new DateTime('now');
    }
}
