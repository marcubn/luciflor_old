<?php

  /************************************************************\
  *
  *	  PHP Array Pagination Copyright 2007 - Derek Harvey
  *	  www.lotsofcode.com
  *
  *	  This file is part of PHP Array Pagination .
  *
  *	  PHP Array Pagination is free software; you can redistribute it and/or modify
  *	  it under the terms of the GNU General Public License as published by
  *	  the Free Software Foundation; either version 2 of the License, or
  *	  (at your option) any later version.
  *
  *	  PHP Array Pagination is distributed in the hope that it will be useful,
  *	  but WITHOUT ANY WARRANTY; without even the implied warranty of
  *	  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
  *	  GNU General Public License for more details.
  *
  *	  You should have received a copy of the GNU General Public License
  *	  along with PHP Array Pagination ; if not, write to the Free Software
  *	  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	02111-1307	USA
  *
  \************************************************************/

  class pagination
  {
    var $page = 1; // Current Page
    var $perPage = 10; // Items on each page, defaulted to 10
    var $showFirstAndLast = false; // if you would like the first and last page options.
    
    function generate($array, $perPage = 10)
    {
      // Assign the items per page variable
      if (!empty($perPage))
        $this->perPage = $perPage;
      
      // Assign the page variable
      if (!empty($_GET['page'])) {
        $this->page = $_GET['page']; // using the get method
      } else {
        $this->page = 1; // if we don't have a page number then assume we are on the first page
      }
      
      // Take the length of the array
      $this->length = count($array);
      
      // Get the number of pages
      $this->pages = ceil($this->length / $this->perPage);
      
      // Calculate the starting point 
      $this->start  = ceil(($this->page - 1) * $this->perPage);
      
      // Return the part of the array we have requested
      return array_slice($array, $this->start, $this->perPage);
    }
    
    function links($smart_url)
    {
      // Initiate the links array
      $plinks = array();
      $links = array();
      $slinks = array();
      
      // Concatenate the get variables to add to the page numbering string
      if (count($_GET)) {
        $queryURL = '';
        foreach ($_GET as $key => $value) {
          if ($key != 'page') {
            $queryURL .= '&'.$key.'='.$value;
          }
        }
      }
      $plinks[] = '<span class="pg_prod">Pagina</span><ul class="ppp">';
      // If we have more then one pages
      if (($this->pages) > 1)
      {
        // Assign the 'previous page' link into the array if we are not on the first page
        if ($this->page != 1) {
          if ($this->showFirstAndLast) {
            //$plinks[] = " <li><span class='aktualAktywnaStrona'>&lt;</span></li> ";
            //$plinks[] = " <li><a href='/{$smart_url}/pagina-1.html'>&laquo;&laquo; Prima </a></li> ";
          }
          //$plinks[] = " <li class='first_item_pag'><a href='/{$smart_url}/pagina-" . ($this->page - 1 ) . ".html'>< Pagina precedenta</a></li> ";
          //$plinks[] = " <li><span class='aktualAktywnaStrona'>&lt;</span></li> ";
          //$plinks[] = " <li><span class='aktualAktywnaStrona'><a style='text-decoration: none;' href='/{$smart_url}/pagina-" . ($this->page - 1 ) . ".html'>&lt;</a></span></li> ";
        }
        //$plinks[] = "</ul>";
        // Assign all the page numbers & links to the array
        //$links[] = "<div class='interior_paginare'>";
        for ($j = 1; $j < ($this->pages + 1); $j++) {
          if ($this->page == $j) {
            //$links[] = ' <a class="active">'.$j.'</a> '; // If we are on the same page as the current item
            $links[] = '<li class="active"><a href="#">'.$j.'</a></li>'; // If we are on the same page as the current item
          } else {
            //$links[] = " <a href='/{$smart_url}/pagina-{$j}.html'>$j</a> ";
            $links[] = "<li><a href='/{$smart_url}/pagina-{$j}.html'>$j</a></li>";
          }
        }
        //$links[] = "</div>";
        //$slinks[] ="<ul class='right_paginare'>";
        // Assign the 'next page' if we are not on the last page
        if ($this->page < $this->pages) {
          //$slinks[]  = " <li class='last_item_pag'><a href='/{$smart_url}/pagina-" . ($this->page + 1 ) . ".html'>Pagina urmatoare ></a><li> ";
          //$slinks[] =  " <li><a href='/{$smart_url}/pagina-{$this->pages}.html' class='aktualStronicLink'>&gt;</a></li> ";
          //$slinks[] =  " <li><a style='text-decoration: none; color: ' href='/{$smart_url}/pagina-" . ($this->page + 1 ) . ".html'><span class='aktualAktywnaStrona'>&gt;</span></a></li> ";
            //$slinks[]  = " <li><a class='arrow_right' href='/{$smart_url}/pagina-" . ($this->page + 1 ) . ".html'></a></li> ";
            //$slinks[]  = " <a href='/{$smart_url}/pagina-" . ($this->page + 1 ) . ".html'><img src='/images/arrow_right_light_blue.png'/></a> ";
          if ($this->showFirstAndLast) {
            //$slinks[] =  " <li><a href='/{$smart_url}/pagina-{$this->pages}.html'> Ultima &raquo;&raquo; </a></li> ";
            //$slinks[] =  " <li><a href='/{$smart_url}/pagina-{$this->pages}.html' class='aktualStronicLink'>&gt;</a></li> ";
          }
        }
        $slinks[] = "</ul>";
        // Push the array into a string using any some glue
        return implode(' ', $plinks).implode(' ', $links).implode(' ', $slinks);
        //return implode(' ', $plinks).implode($this->implodeBy, $links).implode(' ', $slinks);
      }
      return;
    }
    
   function links_filtered($smart_url)
    {
      // Initiate the links array
      $plinks = array();
      $links = array();
      $slinks = array();
      
      // Concatenate the get variables to add to the page numbering string
      if (count($_GET)) {
        $queryURL = '';
        foreach ($_GET as $key => $value) {
          if ($key != 'page') {
            $queryURL .= '&'.$key.'='.$value;
          }
        }
      }
      
      // If we have more then one pages
      if (($this->pages) > 1)
      {
        // Assign the 'previous page' link into the array if we are not on the first page
        if ($this->page != 1) {
          if ($this->showFirstAndLast) {
            $plinks[] = " <a href='{$smart_url}&page=1'>&laquo;&laquo; Prima </a> ";
          }
          $plinks[] = " <a href='{$smart_url}&page=" . ($this->page - 1 ) . "'>&laquo; Inapoi</a> ";
        }
        
        // Assign all the page numbers & links to the array
        for ($j = 1; $j < ($this->pages + 1); $j++) {
          if ($this->page == $j) {
            $links[] = ' <a class="selected">'.$j.'</a> '; // If we are on the same page as the current item
          } else {
            $links[] = " <a href='{$smart_url}&page={$j}'>$j</a> ";
          }
        }
  
        // Assign the 'next page' if we are not on the last page
        if ($this->page < $this->pages) {
          $slinks[]  = " <a href='{$smart_url}&page=" . ($this->page + 1 ) . "'>&raquo; Inainte</a> ";
          if ($this->showFirstAndLast) {
            $slinks[] =  " <a href='{$smart_url}&page={$this->pages}'> Ultima &raquo;&raquo; </a> ";
          }
        }
        
        // Push the array into a string using any some glue
        return implode(' ', $plinks).implode($this->implodeBy, $links).implode(' ', $slinks);
      }
      return;
    }
    
  	function links_alte($smart_url)
    {
      // Initiate the links array
      $plinks = array();
      $links = array();
      $slinks = array();
      
      // Concatenate the get variables to add to the page numbering string
      if (count($_GET)) {
        $queryURL = '';
        foreach ($_GET as $key => $value) {
          if ($key != 'page') {
            $queryURL .= '&'.$key.'='.$value;
          }
        }
      }
      
      // If we have more then one pages
      if (($this->pages) > 1)
      {
        // Assign the 'previous page' link into the array if we are not on the first page
        if ($this->page != 1) {
          if ($this->showFirstAndLast) {
            $plinks[] = " <a href='/{$smart_url}/pagina-1/'>&laquo;&laquo; Prima </a> ";
          }
          $plinks[] = " <a href='/{$smart_url}/pagina-" . ($this->page - 1 ) . "/'>&laquo; Inapoi</a> ";
        }
        
        // Assign all the page numbers & links to the array
        for ($j = 1; $j < ($this->pages + 1); $j++) {
          if ($this->page == $j) {
            $links[] = ' <a class="selected">'.$j.'</a> '; // If we are on the same page as the current item
          } else {
            $links[] = " <a href='/{$smart_url}/pagina-{$j}/'>$j</a> ";
          }
        }
  
        // Assign the 'next page' if we are not on the last page
        if ($this->page < $this->pages) {
          $slinks[]  = " <a href='/{$smart_url}/pagina-" . ($this->page + 1 ) . "/'>&raquo; Inainte</a> ";
          if ($this->showFirstAndLast) {
            $slinks[] =  " <a href='/{$smart_url}/pagina-{$this->pages}/'> Ultima &raquo;&raquo; </a> ";
          }
        }
        
        // Push the array into a string using any some glue
        return implode(' ', $plinks).implode($this->implodeBy, $links).implode(' ', $slinks);
      }
      return;
    }
      
  function links_en($smart_url)
    {
      // Initiate the links array
      $plinks = array();
      $links = array();
      $slinks = array();
      
      // Concatenate the get variables to add to the page numbering string
      if (count($_GET)) {
        $queryURL = '';
        foreach ($_GET as $key => $value) {
          if ($key != 'page') {
            $queryURL .= '&'.$key.'='.$value;
          }
        }
      }
      $plinks[] = "<ul class='left_paginare'>";
      // If we have more then one pages
      if (($this->pages) > 1)
      {
        // Assign the 'previous page' link into the array if we are not on the first page
        if ($this->page != 1) {
          if ($this->showFirstAndLast) {
            $plinks[] = " <li><a href='/{$smart_url}/page-1.html'>&laquo;&laquo; First </a></li> ";
          }
          $plinks[] = " <li class='first_item_pag'><a href='/{$smart_url}/page-" . ($this->page - 1 ) . ".html'>< Previous page</a></li> ";
          //$plinks[] = " <li><a class='arrow_left' href='/{$smart_url}/pagina-" . ($this->page - 1 ) . ".html'></a></li> ";
          //$plinks[] = " <a href='/{$smart_url}/pagina-" . ($this->page - 1 ) . ".html'><img src='/images/arrow_left_light_blue.png'/></a> ";
        }
        $plinks[] = "</ul>";
        // Assign all the page numbers & links to the array
        $links[] = "<div class='interior_paginare'>";
        for ($j = 1; $j < ($this->pages + 1); $j++) {
          if ($this->page == $j) {
            $links[] = ' <a class="active">'.$j.'</a> '; // If we are on the same page as the current item
          } else {
            $links[] = " <a href='/{$smart_url}/page-{$j}.html'>$j</a> ";
          }
        }
        $links[] = "</div>";
        $slinks[] ="<ul class='right_paginare'>";
        // Assign the 'next page' if we are not on the last page
        if ($this->page < $this->pages) {
          $slinks[]  = " <li class='last_item_pag'><a href='/{$smart_url}/page-" . ($this->page + 1 ) . ".html'>Next page ></a><li> ";
            //$slinks[]  = " <li><a class='arrow_right' href='/{$smart_url}/pagina-" . ($this->page + 1 ) . ".html'></a></li> ";
            //$slinks[]  = " <a href='/{$smart_url}/pagina-" . ($this->page + 1 ) . ".html'><img src='/images/arrow_right_light_blue.png'/></a> ";
          if ($this->showFirstAndLast) {
            $slinks[] =  " <li><a href='/{$smart_url}/page-{$this->pages}.html'> Last &raquo;&raquo; </a></li> ";
          }
        }
        $slinks[] = "</ul>";
        // Push the array into a string using any some glue
        return implode(' ', $plinks).implode(' ', $links).implode(' ', $slinks);
        //return implode(' ', $plinks).implode($this->implodeBy, $links).implode(' ', $slinks);
      }
      return;
    }
      
      
  }
?>