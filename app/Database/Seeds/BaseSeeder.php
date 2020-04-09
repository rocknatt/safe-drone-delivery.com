<?php 

namespace App\Database\Seeds;

use CodeIgniter\Config\BaseConfig;

class BaseSeeder extends \CodeIgniter\Database\Seeder
{
    /**
     * Lorem random string
     */
    protected $lorem = '';

    public function __construct(BaseConfig $config, BaseConnection $db = null)
    {
        parent::__construct($config, $db);

        $this->lorem = explode(' ', preg_replace('#[^a-zA-Z\d\s:]#', '', $this->text));
    }

    /**
     * Generate a random string
     *
     * @param int length
     * @return string
     */
    protected function randomString($length = 12)
    {
        $text = '';
        $possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for ($i = 0; $i < $length; $i++) {
            //Select char_at
            $text .= $possible{rand(0, strlen($possible) - 1)};
        }

        return $text;
    }

    /**
     * Generate a random latitude
     *
     * @param int min
     * @param int max
     * @return int
     * @protected
     */
    protected function randomNumber($min = 0, $max = 1000)
    {
        return rand(floor($min), floor($max));
    }

    /**
     * Returns a random element of the array
     *
     * @param array $a
     * @return mixed
     */
    protected function pickOne($a)
    {
        return $a[rand(0, count($a) - 1)];
    }

    protected function generateString($short = false)
    {
        $words = array($this->pickOne($this->lorem));
        if ($this->randomNumber(0, 2) >= 1) array_push($words, $this->pickOne($this->lorem));
        if (!$short && $this->randomNumber(0, 3) >= 2) array_push($words, $this->pickOne($this->lorem));

        if (!$short) {
            $i_end = $this->randomNumber(0, count($this->lorem));
            for ($i=0; $i < $i_end; $i++) { 
                array_push($words, $this->pickOne($this->lorem));
            }
        }
        

        foreach ($words as $key => $value) {
            $words[$key] = ucfirst($words[$key]);
        }

        return implode(' ', $words);
    }

    private $text = 'In hac habitasse platea dictumst. Duis sollicitudin velit quis orci fermentum finibus. Nullam vitae porta ligula. Vivamus sagittis mattis dignissim. Sed ut vehicula orci, nec iaculis nisl. Nunc interdum eros vel lacus aliquam porttitor. Nulla placerat a massa eget fermentum. Donec vitae luctus lacus, in cursus purus. Maecenas sit amet viverra odio. Mauris pharetra felis dictum tortor molestie, sed varius leo rutrum. Maecenas pellentesque orci mollis massa rutrum eleifend. Vivamus at diam hendrerit, commodo leo nec, eleifend magna. Curabitur ac finibus risus, ut malesuada mi. Donec sed nunc ut magna porta aliquam id ac risus. Phasellus sed lobortis nibh, vel ullamcorper magna. Morbi sagittis justo eu dolor elementum, a fermentum ipsum varius. Praesent ac porta nisl, a ullamcorper odio. Quisque convallis accumsan laoreet. Suspendisse blandit lectus blandit lacinia aliquam. Morbi euismod vehicula tempor. Etiam felis augue, scelerisque sit amet blandit a, pulvinar sit amet metus. Aliquam erat volutpat. Maecenas rutrum, odio eget consequat pretium, lectus est consectetur eros, ultricies faucibus mi libero a leo. Etiam elementum risus et libero sollicitudin hendrerit. Etiam ut euismod dolor. Fusce ac tellus tortor. Duis lacinia molestie magna, id sollicitudin est dictum quis. Mauris a est nisl. Donec sed tellus in turpis finibus tristique. Integer id velit non mi rhoncus pellentesque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris ut orci purus. Vivamus iaculis placerat magna, eu euismod erat molestie ut. Vestibulum et purus lobortis, pharetra justo et, fringilla nulla. Sed arcu nulla, placerat sed egestas in, sodales id lacus. Sed bibendum, felis tempor placerat porttitor, lectus neque porta neque, quis accumsan nibh nulla vel sapien. Quisque augue sapien, lacinia eu sem eget, sodales mattis sem. Nullam pretium mi eu est tincidunt interdum. Curabitur venenatis, magna vel vestibulum euismod, enim justo fringilla urna, non finibus dui arcu id nisl. Fusce in risus vitae ligula porta placerat eget in urna. Donec rhoncus orci ac magna venenatis posuere. Duis ut luctus dui, a posuere neque. Nam mollis tristique sodales. Curabitur sit amet ullamcorper risus, ut ornare erat. Vivamus sed nunc quis odio mattis posuere. In hac habitasse platea dictumst. Fusce feugiat gravida commodo. Donec finibus varius eros, eget ultrices lorem sodales et. In ligula dolor, blandit a tristique ac, sagittis eu nulla. Vestibulum fringilla, elit eu commodo pulvinar, erat nulla ultricies quam, eget lacinia lectus elit ultrices purus. Donec pretium mi in mauris tincidunt sodales. Etiam eu lacus mauris. Phasellus sagittis vulputate odio, vel ultricies massa semper vel. Proin id tincidunt sem, eu fringilla lectus. Proin augue lectus, tempor et gravida ac, sollicitudin a magna. Vestibulum porttitor sapien sed commodo rhoncus. Phasellus id vehicula eros, ac pharetra augue. Aenean felis eros, auctor eget neque a, dignissim sagittis risus. Nullam ultricies elit sit amet nunc congue scelerisque. Mauris at lorem odio. Suspendisse potenti. Nam pretium nec libero sed fermentum. Sed a quam efficitur, accumsan urna vel, aliquet urna. Nulla imperdiet pretium augue nec venenatis. Nunc et congue ipsum. Cras sit amet velit ante. Cras malesuada et nisl id faucibus. Vestibulum purus turpis, euismod in aliquam non, commodo quis sem. Nulla dictum non sem et porta. Proin nec sem nec est maximus lacinia. Proin quis ultricies massa. Cras porttitor finibus odio. Mauris sit amet odio nec lacus eleifend bibendum. Fusce tortor sem, accumsan vitae cursus ac, congue a elit. Ut malesuada nunc convallis, pharetra enim at, pharetra felis. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed fermentum neque dolor, vel finibus orci mollis vel. Praesent iaculis quam vel nunc ultrices egestas. Donec sit amet consectetur lorem, ac consectetur massa. Nullam semper tristique luctus. Sed placerat justo orci, vitae commodo nulla mattis faucibus. Aenean gravida pretium consequat. Pellentesque hendrerit nisi sit amet est condimentum, vel rutrum eros commodo. Curabitur id rutrum diam. Aliquam luctus iaculis ligula, in lacinia ligula finibus ut. Integer dictum sem eget nisl mollis tempor. Vivamus sed justo lacinia, condimentum turpis nec, fringilla ante. Fusce viverra est quis justo posuere scelerisque. Curabitur metus risus, pharetra id cursus at, blandit ut turpis. Praesent sit amet diam nisl. Nulla venenatis sodales mauris, eu consequat nulla convallis sit amet. Sed placerat ipsum eu nisi mattis, id tincidunt arcu mattis. Praesent at nisi vitae tortor pharetra semper. Suspendisse finibus ultrices lacinia. Donec in nulla auctor, vestibulum ex ac, interdum massa. Praesent placerat tellus vel posuere cursus. Pellentesque consequat cursus tellus, eu interdum augue sollicitudin eget. Etiam rhoncus nisi in justo venenatis, a fermentum elit dictum. Pellentesque eget metus ut quam hendrerit convallis. Etiam in odio a mi commodo fringilla feugiat quis neque. In non mollis diam, vitae auctor mauris. Vivamus suscipit nisl et dui tempus, gravida fermentum ante eleifend. Proin eget purus vitae est lobortis rutrum. Curabitur lacinia sem non neque feugiat, eu hendrerit nibh aliquet. Donec vel magna orci. Proin vestibulum tempor orci. Donec purus tortor, rutrum a pellentesque a, consectetur ut turpis. Maecenas vel sodales augue, id pharetra lorem. Vestibulum laoreet neque vel convallis ornare. Nulla augue ipsum, vehicula vel vulputate eu, dapibus vel augue. Nulla facilisi. Morbi sed erat sit amet ligula vestibulum porta non eget magna. Aenean vehicula iaculis velit, et laoreet nibh pharetra eu. Mauris pulvinar leo vitae lectus molestie porttitor. Donec eget nisi euismod, imperdiet leo et, consectetur eros. Ut convallis aliquam augue vel dictum. Nunc ornare diam facilisis magna rutrum mattis. Vivamus in lacus fermentum leo aliquet pretium. Ut accumsan nulla tellus, vel lobortis risus varius vel. In hac habitasse platea dictumst. Nulla facilisi. Praesent posuere quam ac tellus mattis varius. Pellentesque leo mauris, ultricies sit amet molestie in, aliquet eleifend felis. Aliquam ut tincidunt quam, cursus molestie tellus. Mauris dignissim luctus arcu, quis ullamcorper turpis ultricies eu. Aenean tincidunt, magna finibus porta porttitor, metus est porttitor eros, ac feugiat felis ligula et magna. Sed volutpat lectus non eros dignissim auctor sagittis et erat. Morbi dignissim tempus fringilla. Nunc iaculis turpis in pulvinar venenatis. Integer fringilla iaculis velit. Quisque eleifend aliquam tempus. Nunc dictum dolor ligula, id sollicitudin quam pharetra ut. Sed ac erat dictum, lobortis metus quis, posuere leo. Fusce ullamcorper nunc non felis.';
}