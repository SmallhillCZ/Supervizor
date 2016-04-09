<?php

/*
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette;

/**
 * Class BudgetItem
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="budget_item")
 */
class BudgetItem extends Nette\Object
{

    use Identifier;

    /**
     * @var int
     * @ORM\Column(type="integer",nullable=true)
     */
    private $identifier;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $name;

    /**
     * @var BudgetGroup
     * @ORM\ManyToOne(targetEntity="BudgetGroup", inversedBy="BudgetItem")
     * @ORM\JoinColumn(name="budgetgroup_id", referencedColumnName="id", nullable=true)
     */
    private $budgetGroup;
    
    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $created;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $updated;

    /**
     * Invoice constructor.
     * @param string $identifier
     * @param string $name
     */
    public function __construct(BudgetGroup $budgetGroup, $identifier, $name)
    {
        $this->budgetGroup = $budgetGroup;
        $this->identifier = $identifier;
        $this->name = $name;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = $this->updated = new \DateTime();
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime();
    }

}