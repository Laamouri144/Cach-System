#!/usr/bin/env python3
"""
Simple produce helper script.

Usage: python produce.py <customerNumber> <orderNumber>

This script prints a JSON message to stdout and, if kafka-python is installed
and a Kafka broker is reachable at localhost:9092 (or broker:9092 from inside
container), it will attempt to send the message to topic `cache`.
"""
import sys
import json
from datetime import datetime

def main():
    if len(sys.argv) < 3:
        print('Usage: produce.py <customerNumber> <orderNumber>')
        sys.exit(1)

    customer, order = sys.argv[1], sys.argv[2]
    message = {
        'customerNumber': customer,
        'orderNumber': order,
        'timestamp': datetime.utcnow().isoformat() + 'Z'
    }

    print('Produced message:')
    print(json.dumps(message))

    # Optional Kafka send if kafka-python available
    try:
        from kafka import KafkaProducer
        # Try broker hostname with internal port for Docker network
        try:
            producer = KafkaProducer(
                bootstrap_servers=['broker:29092'], 
                value_serializer=lambda v: json.dumps(v).encode('utf-8')
            )
        except:
            # Fallback to external port
            producer = KafkaProducer(
                bootstrap_servers=['broker:9092'], 
                value_serializer=lambda v: json.dumps(v).encode('utf-8')
            )
        producer.send('cache', message)
        producer.flush()
        print('Sent to Kafka topic `cache`.')
    except Exception as e:
        print('Kafka produce skipped (kafka-python or broker not available):', str(e))

if __name__ == '__main__':
    main()
