import { Injectable } from '@angular/core';
import {Team} from "./team";

@Injectable({
  providedIn: 'root'
})
export class TeamService {

  url = 'http://localhost:80/api/results';



  constructor() { }

  getSampleTeamResults() {
    return [
      {
        id: 1,
        name: 'Barcelona',
        logo: 'https://upload.wikimedia.org/wikipedia/en/4/47/FC_Barcelona_%28crest%29.svg',
        matchesPlayed: 6,
        matchesWon: 5,
        matchesDrawn: 0,
        matchesLost: 1,
        goalsFor: 15,
        goalsAgainst: 5,
        goalDifference: 10,
        points: 15
      },
      {
        id: 2,
        name: 'Real Madrid',
        logo: 'https://upload.wikimedia.org/wikipedia/en/5/56/Real_Madrid_CF.svg',
        matchesPlayed: 6,
        matchesWon: 4,
        matchesDrawn: 1,
        matchesLost: 1,
        goalsFor: 12,
        goalsAgainst: 6,
        goalDifference: 6,
        points: 13
      },
      {
        id: 3,
        name: 'Atletico Madrid',
        logo: 'https://upload.wikimedia.org/wikipedia/en/9/93/Atletico_Madrid_2017_logo.svg',
        matchesPlayed: 6,
        matchesWon: 3,
        matchesDrawn: 2,
        matchesLost: 1,
        goalsFor: 10,
        goalsAgainst: 6,
        goalDifference: 4,
        points: 11
      },
      {
        id: 4,
        name: 'Sevilla',
        logo: 'https://upload.wikimedia.org/wikipedia/en/8/86/Sevilla_FC_logo.svg',
        matchesPlayed: 6,
        matchesWon: 2,
        matchesDrawn: 3,
        matchesLost: 1,
        goalsFor: 8,
        goalsAgainst: 6,
        goalDifference: 2,
        points: 9
      },
      {
        id: 5,
        name: 'Villarreal',
        logo: 'https://upload.wikimedia.org/wikipedia/en/7/70/Villarreal_CF_logo.svg',
        matchesPlayed: 6,
        matchesWon: 2,
        matchesDrawn: 2,
        matchesLost: 2,
        goalsFor: 6,
        goalsAgainst: 6,
        goalDifference: 0,
        points: 8
      },
      ]
  }

  async getTeamResults(): Promise<Team[]>{
    try {
      const response = await fetch(this.url);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json(); 
      const teams: Team[] = Object.values(data);
      console.log('Teams:', teams);
      return teams;
    } catch (error) {
      console.error('Failed to load all teams:', error);
      return [];
    }
  }
}
