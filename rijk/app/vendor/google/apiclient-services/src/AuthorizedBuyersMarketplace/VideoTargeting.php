<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\AuthorizedBuyersMarketplace;

class VideoTargeting extends \Google\Collection
{
  protected $collection_key = 'targetedPositionTypes';
  public $excludedPositionTypes;
  public $targetedPositionTypes;

  public function setExcludedPositionTypes($excludedPositionTypes)
  {
    $this->excludedPositionTypes = $excludedPositionTypes;
  }
  public function getExcludedPositionTypes()
  {
    return $this->excludedPositionTypes;
  }
  public function setTargetedPositionTypes($targetedPositionTypes)
  {
    $this->targetedPositionTypes = $targetedPositionTypes;
  }
  public function getTargetedPositionTypes()
  {
    return $this->targetedPositionTypes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VideoTargeting::class, 'Google_Service_AuthorizedBuyersMarketplace_VideoTargeting');
