import sys
import flights_scraping
from playwright.sync_api import sync_playwright

if __name__ == '__main__':
    if len(sys.argv) != 6:
        print('Usage: python plane_calculator.py <origin> <destination> <departure_date> <return_date> <passengers>')
        sys.exit(1) # 

    origin = sys.argv[1]
    destination = sys.argv[2]
    departure_date = sys.argv[3]
    return_date = sys.argv[4]
    passengers = int(sys.argv[5])
    print(f'Calculating best flight for {passengers} passengers from {origin} to {destination} on {departure_date} and returning on {return_date}')
    with sync_playwright() as playwright:
        flights_scraping.run(playwright, origin, destination, departure_date, return_date, passengers)