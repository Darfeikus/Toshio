export interface JwtResponseI {
  dataUser: {
    id: number;
    mat: string;
    type: string;
    accessToken: string;
    expiresIn: string;
  };
}
