<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;
use App\Models\Setting\Group;
use Illuminate\Database\Eloquent\Model;

interface GroupRepositoryInterface
{
  /**
   * Show feature list by given term of search
   *
   * @param \Illuminate\Http\Request $request
   * @return array [response, error]
   */
  public function list(Request $request): array;

  /**
   * Create and return an un-saved model instance
   *
   * @param array $attributes
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function make(array $attributes): Model;

  /**
   * Fill the model with an array of attributes.
   *
   * @param string $slug
   * @param array $attributes
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function fillBySlug($slug, array $attributes): Model;

  /**
   * Create or update a feature of models and persist them to the database
   *
   * @param \Illuminate\Database\Eloquent\Model $eloquentModel
   * @param string $mode
   * @param array $features
   * @return array [response, error]
   */
  public function store(Model $eloquentModel, array $features, $mode = 'insert'): array;

  /**
   * Find feature by given slug_path
   *
   * @param  string $slug
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function findBySlug($slug): Group | null;

  /**
   * Delete a feature of models and persist them to the database
   *
   * @param \Illuminate\Database\Eloquent\Model $eloquentModel
   * @return array [response, error]
   */
  public function drop(Model $eloquentModel): array;

  /**
   * Get group by given by code
   *
   * @param array|string $code
   * @return mixed
   */
  public function findByCode(array|string $code) : mixed;

  /**
   * Get other group by given current group code
   *
   * @param string $currentPic
   * @return mixed
   */
  public function findOtherPic(string $currentPic) : mixed;
}
