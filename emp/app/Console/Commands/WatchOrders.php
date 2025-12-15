<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MongoDB\Client;
use MongoDB\Driver\Exception\ConnectionTimeoutException;

class WatchOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch MongoDB for new order changes in real-time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('let s begin the hunt...');
        
        try {
            // Connect to MongoDB replica set
            $uri = 'mongodb://mongo1:27017,mongo2:27018,mongo3:27019/?replicaSet=rs0';
            $client = new Client($uri);
            
            $this->info('Connected to MongoDB');
            
            // Get the collection
            $collection = $client->cache->customersOrders;
            
            $this->line('Watching for new orders...');
            
            // Watch for changes
            $changeStream = $collection->watch();
            
            // Iterate through change stream
            $changeStream->rewind();
            
            while (true) {
                if ($changeStream->valid()) {
                    $change = $changeStream->current();
                    $operationType = $change->operationType;
                    
                    if (in_array($operationType, ['insert', 'update', 'replace'])) {
                        $document = $change->fullDocument;
                        
                        $this->line('gottya');
                        
                        // Convert document to JSON and display
                        $json = json_encode($document, JSON_PRETTY_PRINT);
                        $this->line($json);
                    }
                    
                    $changeStream->next();
                } else {
                    // No changes yet, keep looking
                    $this->line('wheeere aaaaare youuuu ?');
                    sleep(1);
                    $changeStream->next();
                }
            }
            
        } catch (ConnectionTimeoutException $e) {
            $this->error('Connection timeout! Make sure MongoDB replica set is initialized.');
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\MongoDB\Driver\Exception\RuntimeException $e) {
            $this->error('MongoDB Runtime Error: ' . $e->getMessage());
            $this->error('This usually means change streams are not supported (requires replica set).');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Type: ' . get_class($e));
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}

