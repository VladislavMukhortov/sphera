<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Menu extends Component
{
    /**
     *
     * Список элементов меню
     * @var array
     *
     */
    public array $menu = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->addMenu('home', '', 'Панель управления', 'mdi-monitor-dashboard');

        $this->addMenu('skills.index', 'skills', 'Области знаний', 'mdi-robot');

        $this->addMenu('users.index', 'users', 'Пользователи', 'mdi-robot');
//Раздел пока не нужен, отключен.
//        $this->addMenu('moderation.index', 'moderation', 'Модерация', 'mdi-robot');

        $this->addMenu('staff.index', 'staff', 'Администраторы', 'mdi-robot');

        $this->addMenu('settings.index', 'settings', 'Настройки сервиса', 'mdi-robot');
    }

    /**
     * Добавляет элемент меню
     *
     * @param string $routeName
     * @param string $segment
     * @param string $linkName
     * @param string $icon
     * @param string|null $className
     *
     * @return void
     */
    protected function addMenu(string $routeName, string $segment, string $linkName, string $icon, ?string $className = NULL): void
    {
        $canView = !$className || Auth::user()->can('viewAny', $className);

        if ($canView) {
            $this->menu[] = [
                'href' => route($routeName),
                'active' =>  request()->segment(1) == $segment ? 'active' : '',
                'icon' => $icon,
                'text' => $linkName
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.menu');
    }
}
