import { StrategyVerifyCallback } from "remix-auth";
import {
  OAuth2Profile,
  OAuth2Strategy,
  OAuth2StrategyVerifyParams
} from 'remix-auth-oauth2';

export type AuthentikScope =
  | 'openid'
  | 'email'
  | 'profile'
  | 'offline_access'
  ;

export type AuthentikStrategyOptions = {
  clientID: string,
  clientSecret: string,
  callbackURL: string,
  baseURL: string,
  audience: string,
  scope: AuthentikScope[],
};

export type AuthentikProfile = {
  id: string,
  name: string,
  email: string,
  email_verified: boolean,
  expiresAt: number,
  _json: {
    id: string,
    name: string,
    email: string,
    email_verified: boolean,
    exp: number,
  };

} & OAuth2Profile;

export type AuthentikExtraParams = {
  expires_in: 3920;
  token_type: 'Bearer';
  scope: string;
  id_token: string;
} & Record<string, string | number>;

export const AuthentikDefaultScope: AuthentikScope[] = [ 'openid', 'email', 'profile' ];
export const AuthentikScopeSeperator = ' ';

export class AuthentikStrategy<TUser> extends OAuth2Strategy<
  TUser,
  AuthentikProfile,
  AuthentikExtraParams
> {
  public name = 'authentik';
  private userInfoUrl: string;

  constructor(
    {
      clientID,
      clientSecret,
      callbackURL,
      baseURL,
      scope

    }: AuthentikStrategyOptions,
    verify: StrategyVerifyCallback<TUser, OAuth2StrategyVerifyParams<AuthentikProfile, AuthentikExtraParams>>,) {

    const authorizationURL = `${baseURL}/application/o/authorize/`;
    const tokenURL = `${baseURL}/application/o/token/`;

    super(
      {
        clientID,
        clientSecret,
        callbackURL,
        authorizationURL,
        tokenURL,
      },
      verify
    );
    this.userInfoUrl = `${baseURL}/application/o/userinfo/`;
    this.scope = scope.join(AuthentikScopeSeperator) || AuthentikDefaultScope.join(AuthentikScopeSeperator);
  }

  protected authorizationParams(): URLSearchParams {
    if (this.scope) {
      return new URLSearchParams({
        scope: this.scope,
      });
    }

    return new URLSearchParams();
  }

  protected async userProfile(accessToken: string): Promise<AuthentikProfile> {
    const response = await fetch(this.userInfoUrl, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
      }
    });

    const _raw: AuthentikProfile[ '_json' ] = await response.json();
    const profile: AuthentikProfile = {
      id: _raw.id,
      provider: 'authentik',
      name: _raw.name,
      email: _raw.email,
      email_verified: _raw.email_verified,
      expiresAt: _raw.exp,
      _json: _raw
    };

    return profile;
  }
}
