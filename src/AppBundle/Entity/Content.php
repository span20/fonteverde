<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Content
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Content
{
    const SERVER_PATH_TO_IMAGE_FOLDER = 'uploads';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="main_content", type="text")
     */
    private $main_content;
    /**
     * @var string
     *
     * @ORM\Column(name="content_1", type="text")
     */
    private $content_1;

    /**
     * @var string
     *
     * @ORM\Column(name="content_2", type="text")
     */
    private $content_2;

    /**
     * @var string
     *
     * @ORM\Column(name="content_3", type="text")
     */
    private $content_3;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getFileFullPath()
    {
        if (!$this->getFilename()) {
            return false;
        }

        $filename = $this->getFilename();

        return self::SERVER_PATH_TO_IMAGE_FOLDER . "/" . $filename[0] . "/" . $filename[1] . "/" . $filename[2] . "/" . $filename;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        $filename = md5($this->getFile()->getClientOriginalName().time());
        $dir = self::SERVER_PATH_TO_IMAGE_FOLDER."/".$filename[0]."/".$filename[1]."/".$filename[2];

        if (!file_exists($dir)) {
            mkdir(self::SERVER_PATH_TO_IMAGE_FOLDER."/".$filename[0]."/".$filename[1]."/".$filename[2], 0775, true);
        }

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            $dir,
            $filename.".".$this->getFile()->getClientOriginalExtension()
        );

        // set the path property to the filename where you've saved the file
        $this->filename = $filename.".".$this->getFile()->getClientOriginalExtension();

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     */
    public function refreshUpdated()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Content
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Content
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Content
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
     */
    public function setContent1($content)
    {
        $this->content_1 = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent1()
    {
        return $this->content_1;
    }

    /**
     * Set main_content
     *
     * @param string $main_content
     * @return Content
     */
    public function setMainContent($main_content)
    {
        $this->main_content = $main_content;

        return $this;
    }

    /**
     * Get main_content
     *
     * @return string
     */
    public function getMainContent()
    {
        return $this->main_content;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
     */
    public function setContent2($content)
    {
        $this->content_2 = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent2()
    {
        return $this->content_2;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
     */
    public function setContent3($content)
    {
        $this->content_3 = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent3()
    {
        return $this->content_3;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Content
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Content
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
