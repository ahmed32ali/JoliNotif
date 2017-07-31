<?php

/*
 * This file is part of the JoliNotif project.
 *
 * (c) Ahmed Ali <dev.ahmed.abbas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\JoliNotif;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument ;
use Symfony\Component\Console\Input\InputInterface ;
use Symfony\Component\Console\Output\OutputInterface ;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Joli\JoliNotif\NotifierFactory ;
use Joli\JoliNotif\Notifier\NullNotifier ;
use Joli\JoliNotif\Notification ;


class NotificationCli extends Command{

    public function configure()
    {
        $this->setName("notify")
            ->setDescription("Command Line Tool For Showing Notifications")
            ->addArgument("title" , InputArgument::REQUIRED ,"Notification Title")
            ->addArgument("body" , InputArgument::REQUIRED ,"Notification Body")
            ->addArgument("icon"  ,InputArgument::OPTIONAL  ,"Notification Icon") ;
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $title = $input->getArgument("title") ;
        $body = $input->getArgument("body") ;
        $icon = $input->hasArgument("icon") ?  $input->getArgument("icon") : false ;

        if( $icon ){
            $fs = new Filesystem();

            try {
                $fs->exists($icon);
            } catch (IOExceptionInterface $e) {
                $output->writeln("<danger>Image Path is Wrong</danger>");
                exit(1);
            }
        }


        $notifier = NotifierFactory::create();

        if (!($notifier instanceof NullNotifier)) {
            $notification =
                (new Notification())
                    ->setTitle( $title )
                    ->setBody( $body)
                    ->setIcon( $icon );

            $result = $notifier->send( $notification );
        }
    }
}