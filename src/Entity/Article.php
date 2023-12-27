<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Event\ArticleExpiredEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $date_deb = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_fin = null;
    /**
     * @Assert\NotBlank(message="Please enter the start price.")
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4)]
    private ?string $prix_depart = null;

    /**
     * @Assert\NotBlank(message="Please enter the final price.")
     * @Assert\GreaterThan(propertyPath="prix_depart", message="The final price must be greater than the start price.")
     */

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4, nullable: true)]
    private ?string $prix_final = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4, nullable: true)]
    private ?string $prixEnchereMax = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $biddingPrices = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4, nullable: true)]
    private ?string $winningbidingprice = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;
    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


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

    public function getDateFin(): DateTimeImmutable
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeImmutable $date_fin): static
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



    /**
     * @return DateTimeImmutable
     */
    public function getDateDeb(): ?DateTimeImmutable
    {
        return $this->date_deb;
    }

    /**
     * @param \DateTimeImmutable|null $date_deb
     */
    public function setDateDeb(?DateTimeImmutable $date_deb): void
    {
        $this->date_deb = $date_deb ?: new DateTimeImmutable();
    }
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
    public function __toString(): string
{
    return $this->titre ?? 'Untitled Article';
}
}
