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
 * Class Invoice
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="invoice")
 */
class Invoice extends Nette\Object
{

    use Identifier;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="Invoice")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $distinction;

    /**
     * @var bool
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $vatRecord;

    /**
     * @var decimal
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $amount;

    /**
     * @var decimal
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $amountWithoutVat;

    /**
     * @var decimal
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $amountOriginal;

    /**
     * @var decimal
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $amountPaid;

    /**
     * @var decimal
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $amountPaidOriginal;

    /**
     * @var string
     * @ORM\Column(type="string",length=3,nullable=false)
     */
    private $currency;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $issued;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $received;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $maturity;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $paid;

    /**
     * @var string
     * @ORM\Column(type="string",length=6000,nullable=false)
     */
    private $description;

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
     * @param \App\Model\Entities\Supplier $supplier
     * @param string $type
     * @param string $distinction
     * @param bool $vatRecord
     * @param decimal $amount
     * @param decimal $amountWithoutVat
     * @param decimal $amountOriginal
     * @param decimal $amountPaid
     * @param decimal $amountPaidOriginal
     * @param string $currency
     * @param DateTime $issued
     * @param DateTime $received
     * @param DateTime $maturity
     * @param DateTime $paid
     * @param string $description
     */
    public function __construct(Supplier $supplier, $type, $distinction, $vatRecord, $amount, $amountWithoutVat, $amountOriginal, $amountPaid, $amountPaidOriginal, $currency, $issued, $received, $maturity, $paid, $description)
    {
        $this->supplier = $supplier;
        $this->type = $type;
        $this->distinction = $distinction;
        $this->vatRecord = $vatRecord;
        $this->amount = $amount;
        $this->amountWithoutVat = $amountWithoutVat;
        $this->amountOriginal = $amountOriginal;
        $this->amountPaid = $amountPaid;
        $this->amountPaidOriginal = $amountPaidOriginal;
        $this->currency = $currency;
        $this->issued = $issued;
        $this->received = $received;
        $this->maturity = $maturity;
        $this->paid = $paid;
        $this->description = $description;
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