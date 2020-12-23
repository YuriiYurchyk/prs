<?php

namespace App\Observers;

use App\Models\Olx\OlxAd;

class OlxAdObserver
{
    private $loggedAttributes = [
        'title',
        'price',
        'currency',
        'description',
        'publication_at',
        'not_found_at',
        'category',
    ];

    /**
     * Handle the olx ad "created" event.
     *
     * @param \App\Models\Olx\OlxAd $olxAd
     * @return void
     */
    public function created(OlxAd $olxAd)
    {
        $this->createAttributeChangeLogs($olxAd);

    }

    /**
     * Handle the olx ad "updated" event.
     *
     * @param \App\Models\Olx\OlxAd $olxAd
     * @return void
     */
    public function updated(OlxAd $olxAd)
    {
        $this->createAttributeChangeLogs($olxAd);

    }

    public function creating(OlxAd $olxAd)
    {
        //
    }

    public function updating(OlxAd $olxAd)
    {
        //
    }

    /**
     * Handle the olx ad "deleted" event.
     *
     * @param \App\Models\Olx\OlxAd $olxAd
     * @return void
     */
    public function deleted(OlxAd $olxAd)
    {
        //
    }

    /**
     * Handle the olx ad "restored" event.
     *
     * @param \App\Models\Olx\OlxAd $olxAd
     * @return void
     */
    public function restored(OlxAd $olxAd)
    {
        //
    }

    /**
     * Handle the olx ad "force deleted" event.
     *
     * @param \App\Models\Olx\OlxAd $olxAd
     * @return void
     */
    public function forceDeleted(OlxAd $olxAd)
    {
        //
    }

    private function createAttributeChangeLogs(OlxAd $olxAd)
    {
        $changedAttributes = [];
        foreach ($this->loggedAttributes as $attribute) {
            if (!$olxAd->isDirty($attribute) || empty($olxAd->$attribute)) {
                continue;
            }

            $changedAttributes[] = [
                'attribute' => $attribute,
                'value'     => $olxAd->$attribute
            ];
        }

        $olxAd->attributeLogs()->createMany($changedAttributes);
    }
}
