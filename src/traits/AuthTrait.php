<?php

use sergmoro1\user\Module;
use sergmoro1\user\components\SocialContact;
use sergmoro1\user\models\SocialLink;

/**
 * Auth trait.
 */
trait AuthTrait {
    /**
     * OAuth2 callback.
     * 
     * @param string $client OAuth2
     * @return redirect
     */
    public function onAuthSuccess($client)
    {
        $social_contact = new SocialContact($client);

        $social_link = SocialLink::find()->where([
            'source' => $client->getId(),
            'source_id' => $social_contact->id,
        ])->one();
        
        if (Yii::$app->user->isGuest) {
            if ($social_link) { // authorization
                $this->trigger(Module::EVENT_AFTER_LOGGED_IN);
                Yii::$app->user->login($social_link->user);
            } else { // registration
                $social_contact->registration($client->getId());
            }
        } else { // the user is already registered
            if (!$social_link) { // add external service of authentification
                $social_contact->makeLink($client->getId(), Yii::$app->user->id);
            }
        }
    }
}
