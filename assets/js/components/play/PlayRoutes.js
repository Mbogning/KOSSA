import React, { InvalidEvent } from 'react';
import Top5 from '../../components/Top5';
import Banner from '../../components/play/Banner';
import MenuLeft from '../../components/play/MenuLeft';
import axios from 'axios';
import HomePlay from './HomePlay';
import Artiste from './Artiste';
import NotFound from './NotFound';
import { BrowserRouter, NavLink, Route, Switch } from 'react-router-dom';
import Album from './Album';
import Single from './Single';
import Top10 from './Top10';
import PlayList from './PlayList';
import Jaime from './Jaime';
import DetailArtiste from './DetailArtiste';
import DetailAlbum from './DetailAlbum';
import DetailSingle from './DetailSingle';
import * as constant from '../Constant';

class PlayRoutes extends React.Component {
  render() {

    return (

      <div>
        <Switch>
        <Route exact path={constant.PLAY_URL+'/'} component={HomePlay} />  
          <Route exact path={constant.PLAY_URL+'/artist'} component={Artiste} />
          <Route exact path={constant.PLAY_URL + '/artist/genre/:genreSlug'} component={Artiste} />
          <Route path={constant.PLAY_URL+'/artist/details'} component={DetailArtiste} />
          <Route exact path={constant.PLAY_URL+'/album'} component={Album} />
          <Route path={constant.PLAY_URL+'/album/details'} component={DetailAlbum} />
          <Route exact path={constant.PLAY_URL+'/single'} component={Single} />
          <Route path={constant.PLAY_URL+'/single/details'} component={DetailSingle} />
          <Route path={constant.PLAY_URL+'/top10'} component={Top10} />
          <Route path={constant.PLAY_URL+'/playlist'} component={PlayList} />
          <Route path={constant.PLAY_URL+'/jaime'} component={Jaime} />
          <Route component={NotFound} />
        </Switch>
      </div>
    );
  }
}


export default PlayRoutes;



















