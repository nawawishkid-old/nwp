<?php

namespace NWP;

interface HTMLPage {
	public function create();
	public function addStyle();
	public function addScript();
}