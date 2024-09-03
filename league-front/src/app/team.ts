export interface Team {
    id: number;
    name: string;
    logo: string;
    matchesPlayed: number;
    matchesWon: number;
    matchesDrawn: number;
    matchesLost: number;
    goalsFor: number;
    goalsAgainst: number;
    goalDifference: number;
    points: number;
}