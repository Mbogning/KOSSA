import React, { InvalidEvent } from 'react';
import Top5 from '../../components/Top5';
import Banner from '../../components/play/Banner';
import MenuLeft from '../../components/play/MenuLeft';
import Base from '../../components/play/Base';
import ContentRightMidle from '../../components/play/ContentRightMidle';
import axios from 'axios';
import {BrowserRouter, NavLink,Link ,Route, Switch} from 'react-router-dom';

class DetailSingle extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tickets: [],
            loading: false,
            nom: '',
            email: '',
            tel: '',
            prix: 0,
            type: null,
            nombre: 1,
            ticketId: null,
            total: 0,
            errors: {
                name: null,
                email: null,
                tel: null,
                nombre: null
            }

        };

        this.handleTicketSubmit = this.handleTicketSubmit.bind(this);
        this.handleShowPaiement = this.handleShowPaiement.bind(this);
    }

    componentDidMount() {

        /*fetch('/' + window.locale + '/event/json_event_tickets/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                //console.log('tickets...', entries)
                this.setState({
                    tickets: entries,
                    loading: true
                });

            });

         const script = document.createElement("script");          
          script.src = "https://www.wecashup.com/library/MobileMoney.js";
          script.async = true;  
          document.body.appendChild(script);*/
    }

    handleTicketScroll(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('bounceInDown2').removeClass('d-none');
    }


    handlePaiementClick(e) {
        $('#WCUpaymentButton').click();
    }



    handleTicketAction(e, id, prix, type) {

        this.setState({ prix: prix });
        this.setState({ ticketId: id });
        this.setState({ type: type });
        this.setState({ nombre: 1 });

        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');


        if (window.userId > 0 && prix == 0) {//si le user est connecté
            axios.post('/' + window.locale + '/event/json_manage_ticket/' + id, {
                prix: this.state.prix,
            }).then(response => {
                this.setState({ nombre: 1 });

                //console.log('succes....',response.data.message)
                let message = response.data.message;
                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br/><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }).catch(error => {
                this.setState({ nombre: 1 });
                let errorObject = JSON.parse(JSON.stringify(error));
                let message = errorObject.response.data.message;

                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br/><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }
            );
        } else {
            $('.cont-tik').removeClass('d-none sout').addClass('siut');
        }

    }

    handleShowPaiement(e) {
        //console.log('nianag....................')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }

        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
        //$(this).parent().parent().addClass('d-none b-tkt');
        $('.cont-tik').removeClass('siut').addClass('d-none');
        //on acitve la dialogue du paiement
        $('.cont-pay').removeClass('d-none sout').addClass('siut');
    }

    handleTicketSubmit(e) {

        console.log('je sousmet le form')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }


        axios.post('/' + window.locale + '/event/json_manage_ticket/' + this.state.ticketId, {
            email: this.state.email,
            nom: this.state.nom,
            tel: this.state.tel,
            nombre: this.state.nombre,
            prix: this.state.prix,
        }).then(response => {
            this.setState({ nombre: 1 });

            //console.log('succes....',response.data.message)
            let message = response.data.message;
            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br/><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }).catch(error => {
            console.log('error....', error)
            this.setState({ nombre: 1 });
            let errorObject = JSON.parse(JSON.stringify(error));
            let message = errorObject.response.data.message;

            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br/><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }
        );
    }

    handleContentChange(e) {
        const name = e.target.name;
        const value = e.target.value;
        this.setState({ [name]: value });
    }

    validate(name, tel, email, nombre) {
        let token = null;
        let errors = {};


        if (this.state.prix > 0) {//ticket payant
            if (nombre <= 0) {
                errors.nombre = " :Le nombre de tickets doit etre au  moins 1";
                token = "errors";
            }

            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        } else { //ticket gratuit
            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (tel.length === 0) {
                    errors.tel = " :Veuillez renseigner votre téléphone";
                    token = "errors";
                }


                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        }

        this.setState({ errors: errors });
        return token;
    }


    render() {
        const website = `http://${window.location.hostname}:${window.location.port}/`;
        const urlpay = website + window.locale + '/pay/wecashup';
        const logo = website + '/kossa/img/kossa.png';

        const standley = '/kossa/img/enow2.jpg';
        return (
            <Base
                contentRight={<ContentRightMidle />}
                content={
                    <div>
                  <div className="mb-4 mt-1">
            <div className="pl-4 pt-4 pr-4 pb-3 white toile-content mb-4 position-relative">
                <div className="d-inline-block mb-3" >
                    <div className="weight-mus">
                      <div className="a-play-cont position-relative d-inline-block">
                        <div className="position-absolute"></div>
                        <div className="content-mus weight-mus z-depth-2 toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                        </div>
                        </div>
                    </div>
                </div>
                <div className="d-inline-block mb-2 ml-2 " >
                  <div className="mb-2"><span className="badge purple pl-2 pr-2">Titre du single</span> <br/> <span className="font-weight-bold h3-responsive">Hé père</span></div>
                  <div className="mb-2"><span className="badge blue pl-2 pr-2">Par l'artiste </span> <br/> <span className="font-weight-bold h4-responsive cl-n2">Stansley enow</span></div>
                  <div className=" pr-2">Durée - 03:30</div>
                </div>
                <div className="toile-content mb-3"></div>
                <div className="mb-1 mt-1 text-right">
                  <span className="mr-1"> <a href="detartiste.html" className="font-weight-bold  fs-12 btn-sm tt"><i className="fa fa-list mr-1"></i> Page de l'artiste</a></span>
                  <span className="mr-1"> <a href="" className="font-weight-bold  fs-12 btn-sm bnt-plyay-mus tt"><i className="fa fa-play mr-1"></i> Lire</a></span>
                  <span className="mr-1 position-relative"> 
                    <a href="" className="font-weight-bold  fs-12 btn-sm tt btn-plst"><i className="fa fa-plus mr-1"></i> Playlist</a>
                    <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated"  alb-sng="L'album" style={{right:'0px'}}>
                      
                    </div>
                  </span>    
                </div>
            </div>
            <div className="p-4 white toile-content mt-2 text-left mb-4 ">
                <div className="d-inline-block text-center mr-4 mt-2">
                    <span className="d-inline-block fs-20">125</span><br/>
                    <span className="fs-12 text-muted" >Vues</span>
                </div><a href="#" className="d-inline-block text-center mr-4 mt-2 tt">
                    <i className="far fa-heart fs-20"></i><br/>
                    <span className="fs-12 text-muted" >000 j'aime</span>
                </a><a href="#" className="d-inline-block text-center mr-4 mt-2 tt">
                    <i className="fa fa-thumbs-down fs-20"></i><br/>
                    <span className="fs-12 text-muted" >10 j'aime pas</span>
                </a><a href="#comment" className="d-inline-block text-center mr-4 mt-2 tt">
                    <i className="far fa-comment fs-20"></i><br/>
                    <span className="fs-12 text-muted" >10 Commentaire</span>
                </a><a href="#" className="d-inline-block text-center mr-4 mt-2 tt">
                    <i className="far fa-star fs-20"></i><br/>
                    <span className="fs-12 text-muted" >10 Favoris</span>
                </a><div className="d-inline-block text-center position-relative zit">
                    <div className="d-none position-absolute artiste-share bg-play p-2 rounded-pill z-depth-2 " style={{width: '260px !important', top:'10%', left: '-150%'}}>
                        <div className="">
                          <a  href="" className="btn-social fb-ic mr-3" role="button"><i className="fab fa-lg fa-facebook-f"></i></a><a href="" className="btn-social tw-ic mr-3" role="button"><i className="fab fa-lg fa-twitter"></i></a><a href="" className="btn-social gplus-ic mr-3" role="button"><i className="fab fa-lg fa-google-plus-g"></i></a><a href="" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg fa-linkedin-in"></i></a><a href="" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg  fa-facebook-messenger"></i></a><a href="" className="btn-social ins-ic mr-3" role="button"><i className="fab fa-lg fa-instagram"></i></a><a href="" className="btn-social email-ic" role="button"><i className="far fa-lg fa-envelope"></i></a>
                        </div>
                    </div>
                    <span className="d-inline-block text-center mr-4 mt-2 tt btn-artiste-share tt cur-pointer">
                        <i className="fa fa-share fs-20"></i><br/>
                        <span className="fs-12 text-muted" >partager</span>
                    </span>
                </div>
              </div>
            <div className="pl-4 pt-4 pr-4 pb-3 white toile-content mb-4">
              <div className="h6-responsive font-weight-bold text-muted mb-2">
                Laissez un commentaire
              </div>
              <div>
                <form action="">
                  <textarea name="" id="" cols="30" className="w-100 p-3" placeholder="Rédigez ici" style={{minHeight: '150px'}}></textarea>
                </form>
              </div>
              <div className="text-right" >
                  <button type="button" className="btn position-relative btn-danger btn-sm p-1 pl-3 pr-3 font-weight-bold btn-cate mr-0"><i className="fa fa-share mr-2"></i> Publier
                  </button>
              </div>
            </div>
            <div className="pl-4 pt-4 pr-4 pb-3 white toile-content mb-4" id="comment">
                <div className="mb-4 fs-12"><i className="fa fa-comment ml-2 text-muted "></i> 125 Commentaires</div>
                <div className="row mt-3">
                  <div className="col-3 col-md-2 pr-0">
                    <div className="img-photo-com mb-1 z-depth-1 content-mus rounded-pill" style={{ backgroundImage: `url(${standley})` }}>
                    </div>
                  </div>
                  <div className="col-12 col-md-10 pl-1 pt-2 trait-btm pb-4 fs-13 ls3 lh-16 white">
                    <div className="mb-2 ">
                      <div className="font-weight-bold fs-13"> Ngui hermand <span className="fs-12 text-right cl-n2 font-weight-normal">- Il y'a 2 jours</span></div>
                    </div>
                    Lore, quia magni eveniet modi, quibusdam corrupti sit inventore iusto esse eligendi nam totam, ad nihil assumenda necessitatibus quae atque provident rerum?
                    
                  </div>
                </div>
                <div className="row mt-3">
                    <div className="col-3 col-md-2 pr-0">
                      <div className="img-photo-com mb-1 z-depth-1 content-mus rounded-pill" style={{ backgroundImage: `url(${standley})` }}>
                      </div>
                    </div>
                    <div className="col-12 col-md-10 pl-1 pt-2 trait-btm pb-4 fs-13 ls3 lh-16 white">
                      <div className="mb-2 ">
                        <div className="font-weight-bold fs-13"> Albert ngillian <span className="fs-12 text-right cl-n2 font-weight-normal">- Il y'a 3 jours</span></div>
                      </div>
                      Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore, quia magni eveniet modi, quibusdam corrupti sit inventore iusto esse eligendi nam totam, ad nihil assumenda necessitatibus quae atque provident rerum?
                      <div>
    
                      </div>
                    </div>
                  </div>
                  <div className="trait-btm mb-4 mt-2"></div>
                  <div className="row mt-3">
                      <div className="col-3 col-md-2 pr-0">
                        <div className="img-photo-com mb-1 z-depth-1 content-mus rounded-pill" style={{ backgroundImage: `url(${standley})` }}>
                        </div>
                      </div>
                      <div className="col-12 col-md-10 pl-1 pt-2 fs-13 ls3 lh-16 white">
                        <div className="mb-2 ">
                          <div className="font-weight-bold fs-13"> Lollita ittaka <span className="fs-12 text-right cl-n2 font-weight-normal">- Il y'a 3 jours</span></div>
                        </div>
                         Tempore, quia magni eveniet modi, quibusdam corrupti sit inventore iusto esse eligendi nam totam, ad nihil assumenda necessitatibus quae atque provident rerum?
                        
                      </div>
                    </div>
              </div>
        </div>
     
                  
                   </div>
                }
            >
            </Base>
        );
    }
}

export default DetailSingle;



















