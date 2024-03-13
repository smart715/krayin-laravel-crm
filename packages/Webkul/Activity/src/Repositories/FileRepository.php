<?php

namespace Webkul\Activity\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;

class FileRepository extends Repository
{
    /**
     * Activity repository instance.
     *
     * @var \Webkul\Activity\Repositories\ActivityRepository
     */
    protected $activityRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Activity\Repositories\ActivityRepository  $activityRepository
     * @return void
     */
    public function __construct(
        ActivityRepository $activityRepository,
        Container $container
    ) {
        $this->activityRepository = $activityRepository;

        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Activity\Contracts\File::class;
    }

    /**
     * Upload files.
     *
     * @param  array  $data
     * @return mixed|void
     */
    public function upload(array $data)
    {
        if (! request()->hasFile('file')) {
            return;
        }

        $leadActivity = $this->activityRepository->create([
            'is_done' => 1,
            'type'    => 'file',
            'comment' => $data['comment'],
            'user_id' => auth()->guard()->user()->id,
        ]);

        return parent::create([
            'name'        => $data['name'] ?? request()->file('file')->getClientOriginalName(),
            'path'        => request()->file('file')->store('activities/' . $leadActivity->id),
            'activity_id' => $leadActivity->id,
        ]);
    }
}
