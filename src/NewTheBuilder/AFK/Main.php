<?php

namespace NewTheBuilder\AFK;

use NewTheBuilder\AFK\Command\AfkCommand;
use NewTheBuilder\AFK\Task\AfkTask;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private static Main $main;

    protected function onEnable(): void {
        //Listener
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        //Config
        if (!file_exists($this->getDataFolder() . "Config.yml")) {
            $this->saveResource("Config.yml");
        }
        //Command
        $this->getServer()->getCommandMap()->register("Afk", new AfkCommand());
        //Task
        $config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
        if ($config->get("Afk_Status") === "enable"){
            $this->getScheduler()->scheduleRepeatingTask(new AfkTask(), 5*20);
        }
        //API
        self::$main = $this;
    }
    public static function getInstance() : Main {
        return self::$main;
    }
}