import React, { InvalidEvent } from 'react';
import Top5 from '../../components/Top5';
import Banner from '../../components/play/Banner';
import MenuLeft from '../../components/play/MenuLeft';
import axios from 'axios';
import i18n from '../../components/i18n'
import HomePlay from './HomePlay';
import Artiste from './Artiste';
import {
    HashRouter as Router,
    Route,
    Switch,
} from 'react-router-dom';

class Base extends React.Component {
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


          //console.log('--------------------- ',window.locale)

          //i18n.changeLanguage(window.locale);

          const script = document.createElement("script");
          script.src = "/kossa/js/custom.js";
          script.async = true;
          document.body.appendChild(script);
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

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }).catch(error => {
                this.setState({ nombre: 1 });
                let errorObject = JSON.parse(JSON.stringify(error));
                let message = errorObject.response.data.message;

                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
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

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }).catch(error => {
            console.log('error....', error)
            this.setState({ nombre: 1 });
            let errorObject = JSON.parse(JSON.stringify(error));
            let message = errorObject.response.data.message;

            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
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
            <div>
                {this.props.banner}
                <div className="container">
                    <div className="row ">
                        <div className="col-md-2 mt-5 mb-5 p-md-1">
                            <MenuLeft />
                            <Top5 />
                        </div>
                        <div className="col-md-7 mt-md-5 p-md-0">
                            {this.props.content}
                        </div>
                        <div className="col-md-3 mt-1">
                        {this.props.contentRight}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Base;



















