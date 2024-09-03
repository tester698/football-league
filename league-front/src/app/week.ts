export interface Game {
    id: number;
    week: number;
    home_team: string;
    away_team: string;
    home_team_goals: number;
    away_team_goals: number;
    is_played: boolean;
}

export interface Prediction {
    name: string;
    prediction: number;
}