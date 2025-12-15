#!/usr/bin/env python3
"""Simple Kafka consumer that prints messages from `cache` topic."""
import json
from kafka import KafkaConsumer

def main():
    consumer = KafkaConsumer(
        'cache',
        bootstrap_servers=['localhost:9092'],
        auto_offset_reset='earliest',
        group_id='dev-cache-consumer',
        value_deserializer=lambda m: json.loads(m.decode('utf-8'))
    )

    print('Listening for messages on topic "cache"...')
    for msg in consumer:
        print('Received:', msg.value)

if __name__ == '__main__':
    main()
