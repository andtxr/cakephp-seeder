<?php
App::uses('Shell', 'Console');

/**
 * Class PlantShell
 */
class PlantShell extends AppShell
{

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser
            ->addArgument('model', array(
                'help' => __('The model to be planted.'),
                'required' => true
            ))
            ->addOption('quantity', array(
                'short' => 'q',
                'help' => __('Quantity of seeds to be planted.'),
                'default' => 15
            ))
            ->addOption('truncate', array(
                'short' => 't',
                'help' => __('Truncate the model related table.'),
                'boolean' => true
            ))
            ->description(__('Arguments and options of the Plant shell.'));
        return $parser;
    }

    public function main()
    {
        $model = Inflector::camelize($this->args[0]);
        $this->seedModel($model);
    }

    /**
     * @param $model
     */
    public function seedModel($model)
    {
        $models = App::objects('model');
        $modelExists = in_array($model, $models);

        if (!$modelExists) {
            $this->out(__('<error>Model not found...</error>'));
            return;
        }

        $this->loadModel($model);

        $quantity = $this->params['quantity'];
        $truncate = $this->params['truncate'];

        $this->out(__('<info>Starting the %s model seed process.</info>', $model));
        $this->hr();

        $this->out(__('Checking options and arguments...'));
        if ($truncate == true) {
            $truncated = $this->{$model}->deleteAll(array("{$model}.id !=" => ''));
            $return = ($truncated == true)
                ? __('<info>Table truncated!</info>')
                : __('<warning>The table could not be truncated.</warning>');
            $this->out($return);
        }

        $this->out(__('Building a records collection...'));
        $records = $this->getSeed($model, $quantity);

        $this->out(__('Trying to save the records...'));
        $saved = $this->{$model}->saveMany($records, array('validate' => false));
        $return = ($saved == true)
            ? __('<info>%s records saved!</info>', $quantity)
            : __('<warning>There was a problem while saving the records collection.</warning>');
        $this->out($return);

        $this->hr();
        $this->out('<info>The seed process is done!</info>');
        $this->hr();
    }

    /**
     * @param $model
     * @param $quantity
     * @return mixed
     */
    private function getSeed($model, $quantity)
    {
        $class = "{$model}Seed";
        App::uses($class, 'Config/Seeds');
        return $class::getSeed($quantity);
    }

}
