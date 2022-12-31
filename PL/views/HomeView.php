<?php
namespace Views;

use Components\Sidebar;
use Components\Header;
use Components\Overview;
use Components\Tables\RecentOrdersTable;

class HomeView
{
    public function __construct(public $props = [])
    {
        $this->props = $props;
    }

    public function render()
    {
        $sideBar =  View::render(new Sidebar());
        $header = View::render(new Header(['title' => "Trang chủ"]));
        $overview = View::render(new Overview());
        $recentOrdersTable = View::render(new RecentOrdersTable($this->props));

        $view =  <<<EOT
        <body>
            <div class="container">
                $sideBar
                <div class="main">
                    $header

                    <div class="index">
                        $overview
                        $recentOrdersTable
                    </div>
                </div>
            </div>

            <script>
                handleEvents()
                updateFeature()
            </script>
            </body>
        </html>
        EOT;

        return $view;
    }
}
