#!/Users/hugogoncalves/Developer/websites/projet_L3/website/.venv/bin/python3.12
# -*- coding: utf-8 -*-

import click
from datetime import datetime, timedelta
import flights_scraping
from playwright.sync_api import sync_playwright

# Usage : trainline_cli.py --help

@click.command()
@click.option(
    '--departure', '-d',
    envvar="PARAM1",
    type=str,
    help='departure station (example : Toulouse)',
    required=True,
)
@click.option(
    '--arrival', '-a',
    type=str,
    help='arrival station (example : Bordeaux)',
    required=True,
)
@click.option(
    '--departure-date', '-dd',
    type=str,
    help='departure date \
(format : DD-MM-YYYY)',
    default=(datetime.now() + timedelta(days=3)).strftime("%d-%m-%Y"),
    show_default=True,
)
@click.option(
    '--return-date', '-rd',
    type=str,
    help='return date \
(format : DD-MM-YYYY)',
    default=(datetime.now() + timedelta(days=10)).strftime("%d-%m-%Y"),
    show_default=True,
)
@click.option(
    '--passengers', '-p',
    type=int,
    help='number of passengers',
    default=1,
    show_default=True,
)
@click.option(
    '--verbose', '-v',
    is_flag=True,
    help='verbose mode',
)
def main(departure, arrival, departure_date, return_date, passengers, verbose):
    if verbose:
        print(f'Calculating best flight for {passengers} passengers from {departure} to {arrival} on {departure_date} and returning on {return_date}')
    with sync_playwright() as playwright:
        flights_scraping.run(playwright, departure, arrival, departure_date, return_date, passengers)

if __name__ == '__main__':
    main()