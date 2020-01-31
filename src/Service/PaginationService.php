<?php
namespace  App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class  PaginationService {
    private  $entityClass;
    private  $limit =10;
    private  $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private  $templatePath;

    public function __construct(EntityManagerInterface  $manager, Environment $twig, RequestStack $requestStack, $templatePath)
    {
        $this->route = $requestStack->getCurrentRequest()->attributes->get('_route');
        $this->manager= $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }


    public function getTemplatePath()
    {
        return $this->templatePath;
    }


    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }


    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function display(): void
    {
            $this->twig->display($this->templatePath, [
                'page' => $this->currentPage,
                'pages' => $this->getPages(),
                'route' => $this->route
            ]);
    }

    public function getPages()
    {
        if (empty($this->entityClass)){
            throw  new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer! Utitlisez la methode setEntityClass() de votre objet PaginationService! ");
        }
        // 1) Connaitre le total des enregistrements de la table

        $repo = $this->manager->getRepository(($this->entityClass));
        $total =count( $repo->findAll());
        // 2) Faire la division, l'arrondi et  le renvoyer
        $pages = ceil($total / $this->limit);

        return $pages;

    }

    public function getData()
    {
        if (empty($this->entityClass)){
            throw  new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer! Utitlisez la methode setEntityClass() de votre objet PaginationService! ");
        }
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) Demander au repository de trouver les elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        // 3) Renvoyer les éléments en question
        return $data;

    }


    public function setPage($page): PaginationService
    {
        $this->currentPage = $page;

        return $this;

    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->currentPage;
    }


    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass): PaginationService
    {
        $this->entityClass = $entityClass;

        return $this;

    }


    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): PaginationService
    {
        $this->limit = $limit;

        return $this;
    }


}