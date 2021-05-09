<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddDto
 * @package App\Dto\Request
 */
class AddDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="16")
     *
     * @var string
     */
    public $name;
}
