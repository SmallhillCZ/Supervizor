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

namespace Supervizor\Application;

use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use IPub\VisualPaginator\Components as VisualPaginator;
use Nette\Utils\Paginator;
use Supervizor\Budget\BudgetRepository;
use Supervizor\Invoice\InvoiceRepository;

/**
 * Class SearchPresenter
 */
class SearchPresenter extends Presenter
{
    /** @var BudgetRepository @inject */
    public $budgetRepository;

    /** @var InvoiceRepository @inject */
    public $invoiceRepository;

    /** @var EntityManager @inject */
    public $entityManager;

    /** @var int @persistent */
    public $page = 1;

    /** @var string @persistent */
    public $query;

    /**
     * Create items paginator
     *
     * @return VisualPaginator\Control
     */
    protected function createComponentVisualPaginator()
    {
        // Init visual paginator
        $control = new VisualPaginator\Control;
        $control->disableAjax(); //!FIXME
        $control->setTemplateFile('bootstrap.latte');
        return $control;
    }

    public function createComponentFilterForm()
    {
        $form = new Form;

        $form->addText('fromDate');

        $form->addText('toDate');

        $form->addText('query')
            ->setRequired('Zadejte prosim query');

        $form->addSubmit('send');

        $form->onSuccess[] = $this->onFilterFormSuccess;

        return $form;
    }

    public function onFilterFormSuccess(Form $form)
    {
        $values = $form->getValues();
        $this->query = $values->query;
    }

    public function renderDefault()
    {
        $this['visualPaginator']->onShowPage[] = (function ($component, $page) {
            if ($this->isAjax()) {
                $this->redrawControl();
            }
        });

        $visualPaginator = $this['visualPaginator'];
        /** @var Paginator $paginator Get paginator form visual paginator */
        $paginator = $visualPaginator->getPaginator();
        // Define items count per one page
        $paginator->itemsPerPage = 20;
        // Define total items in list
        $paginator->itemCount = $this->invoiceRepository->searchResultTotalCount($this->query);

        $this->template->title = 'Vyhledávání';
        $this->template->found = $paginator->itemCount;
        $this->template->invoices = $this->invoiceRepository->search($this->query, $paginator->itemsPerPage, $paginator->offset);
        $this['filterForm']->setDefaults(['query' => $this->query]);
    }
}
