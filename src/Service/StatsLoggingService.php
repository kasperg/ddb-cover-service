<?php

/**
 * @file
 * Contains the statistics logger.
 */

namespace App\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class StatsLoggingService.
 */
class StatsLoggingService implements LoggerInterface
{
    private $logger;
    private $dispatcher;

    /**
     * StatsLoggingService constructor.
     *
     * @param LoggerInterface          $statsLogger
     *   The logger
     * @param EventDispatcherInterface $dispatcher
     *   The event dispatcher
     */
    public function __construct(LoggerInterface $statsLogger, EventDispatcherInterface $dispatcher)
    {
        $this->logger = $statsLogger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
        $logger = $this->logger;

        $this->dispatcher->addListener(
            KernelEvents::TERMINATE,
            function (TerminateEvent $event) use ($level, $logger, $message, $context) {
                switch ($level) {
                    case LogLevel::EMERGENCY:
                        $logger->emergency($message, $context);
                        break;
                    case LogLevel::ALERT:
                        $logger->alert($message, $context);
                        break;
                    case LogLevel::CRITICAL:
                        $logger->critical($message, $context);
                        break;
                    case LogLevel::ERROR:
                        $logger->error($message, $context);
                        break;
                    case LogLevel::WARNING:
                        $logger->warning($message, $context);
                        break;
                    case LogLevel::NOTICE:
                        $logger->notice($message, $context);
                        break;
                    case LogLevel::INFO:
                        $logger->info($message, $context);
                        break;
                    case LogLevel::DEBUG:
                        $logger->debug($message, $context);
                        break;
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = [])
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = [])
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
