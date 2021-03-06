<?php

/**
 * @file
 * ImageUrl Data Transfer Object (DTO).
 *
 * @see https://api-platform.com/docs/core/dto/
 */

namespace App\Api\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

final class ImageUrl
{
    /**
     * @Groups({"read"})
     */
    private $url;

    /**
     * @Groups({"read"})
     */
    private $format;

    /**
     * @Groups({"read"})
     */
    private $size;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return ImageUrl
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get format.
     *
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * Set format.
     *
     * @param string $format
     *
     * @return ImageUrl
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get size.
     *
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * Set size.
     *
     * @param string $size
     *
     * @return ImageUrl
     */
    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }
}
